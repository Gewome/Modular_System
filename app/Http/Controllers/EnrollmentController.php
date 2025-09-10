<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Mail\EnrollmentApprovedMail;
use App\Mail\EnrollmentRejectedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Program;
use Illuminate\Support\Facades\DB;


class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'middle_name' => 'nullable|string|max:100',
                'suffix_name' => 'nullable|string|max:10',
                'birthdate' => 'required|date',
                'age' => 'required|integer|min:0',
                'gender' => 'required|string|max:10',
                'email' => 'required|email|unique:users,email',
                'phone' => ['nullable', 'regex:/^09[0-9]{9}$/'],
                'address' => 'nullable|string|max:255',
                'citizenship' => 'nullable|string|max:100',
                'religion' => 'nullable|string|max:100',
                'place_of_birth' => 'nullable|string|max:255',
                'civil_status' => 'nullable|string|max:20',
                'spouse_name' => 'nullable|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'mother_name' => 'nullable|string|max:255',
                'guardian' => 'nullable|string|max:255',
                'guardian_contact' => ['nullable', 'regex:/^09[0-9]{9}$/'],
                'program_id' => 'required|string',
                'photo' => 'required|image|mimes:jpg,jpeg,png|max:4096',
                'parent_consent' => 'required|image|mimes:jpg,jpeg,png|max:4096',
                'certify_true' => 'accepted',
                'terms' => 'accepted',
                'qr_pin' => [
                    'required',
                    'string',
                    'size:8',
                    function ($attribute, $value, $fail) {
                        $exists = DB::table('qr_codes')
                            ->where('unique_pin', $value)
                            ->where('is_used', false)
                            ->exists();
                        if (!$exists) {
                            $fail('QR Code PIN is incorrect or already used.');
                        }
                    }
                ],
            ],
            [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'middle_name.max' => 'Middle name cannot exceed 100 characters.',
                'suffix_name.max' => 'Suffix name cannot exceed 10 characters.',
                'birthdate.required' => 'Birthdate is required.',
                'gender.required' => 'Gender is required.',
                'email.required' => 'Email address is required.',
                'email.unique' => 'This email is already registered.',
                'phone.required' => 'Phone number is required.',
                'phone.regex' => 'Phone number must start with 09 and contain 11 digits.',
                'guardian_contact.regex' => 'Guardian contact number must start with 09 and contain 11 digits.',
                'program_id.required' => 'Please select a program.',
                'photo.required' => 'Profile photo is required.',
                'photo.image' => 'Uploaded file must be an image.',
                'photo.mimes' => 'Photo must be JPG, JPEG, or PNG format.',
                'parent_consent.required' => 'Parent consent document is required.',
                'parent_consent.file' => 'Parent consent must be a valid file.',
                'parent_consent.mimes' => 'Parent consent must be JPG, JPEG, or PNG format.',
                'certify_true.accepted' => 'You must certify that your information is true.',
                'terms.accepted' => 'You must agree to the terms and conditions.',
                'qr_pin.required' => 'QR Code PIN is required.',
                'qr_pin.size' => 'QR Code PIN must be exactly 8 characters.',
            ]
        );

        // Custom validation for father, mother, and guardian fields
        $validator->after(function ($validator) use ($request) {
            $fatherName = $request->input('father_name');
            $motherName = $request->input('mother_name');
            $guardian = $request->input('guardian');
            
            // Check if all three fields are empty
            if (empty($fatherName) && empty($motherName) && empty($guardian)) {
                $validator->errors()->add(
                    'father_name', 
                    'At least one of Father\'s Name, Mother\'s Name, or Guardian must be provided.'
                );
                $validator->errors()->add(
                    'mother_name', 
                    'At least one of Father\'s Name, Mother\'s Name, or Guardian must be provided.'
                );
                $validator->errors()->add(
                    'guardian', 
                    'At least one of Father\'s Name, Mother\'s Name, or Guardian must be provided.'
                );
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // ✅ Handle the uploaded profile image
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile_images', 'public');
        } else {
            $photoPath = null;
        }

        // ✅ Handle the uploaded parent consent document
        $parentConsentPath = null;
        if ($request->hasFile('parent_consent')) {
            $parentConsentPath = $request->file('parent_consent')->store('parent_consents', 'public');
        } else {
            $parentConsentPath = null;
        }


        // Calculate batch number based on enrollment date
        $batch_number = \App\Services\BatchNumberService::calculateBatchNumber(now());
        
        // ✅ Create user account first
        $fullName = $request->first_name . ' ' . $request->last_name . ' ' . $request->suffix_name;
        $username = str_replace(' ', '', strtolower($fullName));
        $birthdate = $request->birthdate;

        $user = User::create([
            'name' => $fullName,
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($birthdate),
            'role' => 'student',
            'photo' => $photoPath,
        ]);

        // ✅ Save to enrollments table with student_id
        $enrollment = Enrollment::create([
            'student_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'suffix_name' => $request->suffix_name,
            'birthdate' => $request->birthdate,
            'age' => \Carbon\Carbon::parse($request->birthdate)->diffInYears(now()),
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'citizenship' => $request->citizenship,
            'religion' => $request->religion,
            'place_of_birth' => $request->place_of_birth,
            'civil_status' => $request->civil_status,
            'spouse_name' => $request->spouse_name,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'guardian' => $request->guardian,
            'guardian_contact' => $request->guardian_contact,
            'program_id' => $request->program_id,
            'photo' => $photoPath,
            'parent_consent' => $parentConsentPath,
            'batch_number' => $batch_number,
            'qr_pin' => $request->qr_pin,
        ]);

        // ✅ Mark QR code as used
        DB::table('qr_codes')
            ->where('unique_pin', $request->qr_pin)
            ->update(['is_used' => true, 'used_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => '✅ Enrollment Submitted Successfully! Your account has been created and your enrollment application has been received. You will receive a confirmation email within 48 hours after review. You may now log in to track the status of your enrollment.',
            'username' => $username,
            'password' => $birthdate,
        ], 200);
    }

        public function approve($id, Request $request)
        {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->status = Enrollment::STATUS_APPROVED;
            $enrollment->approved_at = now();
            $enrollment->save();

            // Find the user associated with this enrollment using student_id
            $user = User::find($enrollment->student_id);
            $username = $user ? $user->username : null;
            $password = $user ? $enrollment->birthdate : null; // Or however you set the password

            Mail::to($enrollment->email)->send(new EnrollmentApprovedMail($enrollment, $username, $password));

            return response()->json(['message' => 'Enrollment approved successfully.']);
        }

        public function reject($id, Request $request)
        {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->status = 'Rejected';
            $enrollment->rejected_at = now();
            $enrollment->rejection_reason = $request->input('reason');
            $enrollment->save();

            Mail::to($enrollment->email)->send(new EnrollmentRejectedMail($enrollment, $enrollment->rejection_reason));

            return response()->json(['message' => 'Enrollment rejected successfully.']);
        }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        //
    }
}
