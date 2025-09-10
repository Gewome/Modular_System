<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Enrollment;

class StudentController extends Controller
{
    /**
     * Show the student dashboard.
     */
    public function dashboard()
    {
        $student = Auth::user();

        // Get the latest enrollment (including pending ones)
        $enrollment = $student->enrollments()
            ->with(['program'])
            ->latest()
            ->first();

        // Check if registration fee is paid - this is the primary gatekeeper
        $registrationFeePaid = $this->checkRegistrationFeeStatus($student);

        // Get enrollment status message
        $enrollmentStatusMessage = $this->getEnrollmentStatusMessage($enrollment);

        // Get additional data for dashboard
        $program = $enrollment->program ?? null; // Ensure program is retrieved regardless of enrollment status

        // Get attendance data - only if registration fee is paid
        $attendance = $registrationFeePaid ? $this->getAttendanceData($student) : [
            'total_sessions' => 0,
            'attended_sessions' => 0,
            'attendance_percentage' => 0,
            'last_attended' => '--'
        ];

        // Calculate start date: first attendance date with status 'present'
        // Only calculate if enrollment is approved/enrolled, registration fee is paid, and payment is completed
        $startDate = null;
        $expectedGraduation = null;
        $numberOfSessions = 0;
        $progressPercentage = 0;

        // Always get number of sessions from program (regardless of enrollment/payment status)
        if ($program) {
            // Use program duration as the number of sessions (since duration represents weeks/sessions)
            $numberOfSessions = (int) ($program->duration ?? 0);

            // If duration is not set, fallback to schedules count
            if ($numberOfSessions == 0) {
                $numberOfSessions = $program->schedules()->count();
            }
        }

        // Check if enrollment is approved/enrolled, registration fee is paid, and payment is completed
        $isEnrolledAndPaid = $enrollment &&
                            in_array($enrollment->status, [Enrollment::STATUS_APPROVED, Enrollment::STATUS_ENROLLED]) &&
                            $registrationFeePaid &&
                            $this->checkPaymentStatus($student);

        if ($isEnrolledAndPaid) {
            $firstAttendance = \App\Models\Attendance::where('enrollment_id', $enrollment->id)
                ->where('status', 'present')
                ->orderBy('session_date', 'asc')
                ->first();
            if ($firstAttendance) {
                $startDate = $firstAttendance->session_date;

                // Calculate expected graduation date based on number of sessions and schedule
                if ($program && $numberOfSessions > 0) {
                    // Calculate expected graduation date by adding number of sessions weeks to start date
                    $expectedGraduation = \Carbon\Carbon::parse($startDate)->addWeeks($numberOfSessions);
                }
            }

            // Calculate actual progress percentage based on attended sessions vs total sessions
            if ($numberOfSessions > 0) {
                $attendedSessions = \App\Models\Attendance::where('enrollment_id', $enrollment->id)
                    ->where('status', 'present')
                    ->count();
                $progressPercentage = min(100, round(($attendedSessions / $numberOfSessions) * 100));
            }
        }

        // Get payment data - only if registration fee is paid
        $payments = $registrationFeePaid ? $this->getPaymentData($student) : [
            'total_paid' => 0,
            'total_due' => 0,
            'payment_date' => '--',
            'payment_method' => '--',
            'payment_status' => 'pending',
            'payment_records' => collect([])
        ];

        // Get certificates data - only if registration fee is paid
        $certificates = $registrationFeePaid ? $this->getCertificateData($student) : [
            'total_certificates' => 0,
            'eligible_certificates' => 0,
            'is_eligible' => false,
            'attendance_percentage' => 0,
            'earned_certificates' => []
        ];

        // Get recent activities - only if registration fee is paid
        $recentActivities = $registrationFeePaid ? $this->getRecentActivities($student) : [];

        // Get upcoming sessions - only if registration fee is paid
        $upcomingSessionsData = $registrationFeePaid ? $this->getUpcomingSessions($student) : [
            'upcoming_sessions' => [],
            'this_week_count' => 0
        ];
        $upcomingSessions = $upcomingSessionsData['upcoming_sessions'];
        $thisWeekSessionsCount = $upcomingSessionsData['this_week_count'];

        return view('Student.dashboard', compact(
            'student',
            'enrollment',
            'program',
            'attendance',
            'payments',
            'certificates',
            'recentActivities',
            'enrollmentStatusMessage',
            'startDate',
            'expectedGraduation',
            'numberOfSessions',
            'progressPercentage',
            'upcomingSessions',
            'thisWeekSessionsCount'
        ));
    }

    /**
     * Get enrollment status message based on enrollment status
     */
    private function getEnrollmentStatusMessage($enrollment)
    {
        if (!$enrollment) {
            return null;
        }

        switch ($enrollment->status) {
            case Enrollment::STATUS_PENDING:
                return [
                    'type' => 'info',
                    'message' => 'Your enrollment application is currently pending review. Please wait for admin approval.'
                ];
                
            case Enrollment::STATUS_APPROVED:
                $student = Auth::user();

                // Check registration fee status first
                $registrationFeePaid = $this->checkRegistrationFeeStatus($student);
                $paymentCompleted = $this->checkPaymentStatus($student);

                if (!$registrationFeePaid) {
                    $registrationFee = $enrollment->program ? $enrollment->program->registration_fee : 0;
                    return [
                        'type' => 'error',
                        'message' => '🚫 ACCESS BLOCKED: Your enrollment has been approved, but you MUST pay the registration fee of ₱' . number_format($registrationFee, 2) . ' FIRST before you can access any features, view sessions, or make any other payments. This is a mandatory requirement and cannot be bypassed.'
                    ];
                } elseif (!$paymentCompleted) {
                    return [
                        'type' => 'warning',
                        'message' => 'Your enrollment has been approved! Please complete your session payment to be officially enrolled.'
                    ];
                } else {
                    return null; // No message if all payments are done
                }
                
            case Enrollment::STATUS_ENROLLED:
                return [
                    'type' => 'success',
                    'message' => 'You are successfully enrolled! Welcome to the program.'
                ];
                
            case Enrollment::STATUS_REJECTED:
                return [
                    'type' => 'error',
                    'message' => 'Your enrollment application has been rejected. Please contact admin for more information.'
                ];
                
            default:
                return null;
        }
    }

    /**
     * Get attendance data for the student
     */
    private function getAttendanceData($student)
    {
        $totalSessions = 0;
        $attendedSessions = 0;
        $attendancePercentage = 0;
        $lastAttended = '--';
        
        try {
            // Get the latest enrollment
            $enrollment = $student->enrollments()
                ->with(['program'])
                ->latest()
                ->first();
            
            if ($enrollment && $enrollment->program) {
                // Use program duration as the number of sessions (since duration represents weeks/sessions)
                $totalSessions = (int) ($enrollment->program->duration ?? 0);

                // If duration is not set, fallback to schedules count
                if ($totalSessions == 0) {
                    $totalSessions = $enrollment->program->schedules()->count();
                }

                // If both schedules and duration are 0, provide a default value
                if ($totalSessions == 0) {
                    $totalSessions = 20; // Default number of sessions
                }
                
                // Get actual attended sessions from attendance records
                if (method_exists($enrollment, 'attendances')) {
                    $attendedSessions = $enrollment->attendances()
                        ->where('status', 'present')
                        ->count();
                    
                    // Calculate attendance percentage
                    if ($totalSessions > 0) {
                        $attendancePercentage = min(100, round(($attendedSessions / $totalSessions) * 100));
                    }
                    
                    // Get last attended session
                    $lastAttendance = $enrollment->attendances()
                        ->where('status', 'present')
                        ->orderBy('session_date', 'desc')
                        ->first();
                    
                    if ($lastAttendance) {
                        $lastAttended = $enrollment->program->name . ' - ' . 
                                      \Carbon\Carbon::parse($lastAttendance->session_date)->diffForHumans();
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching attendance data: ' . $e->getMessage());
        }
        
        return [
            'total_sessions' => $totalSessions,
            'attended_sessions' => $attendedSessions,
            'attendance_percentage' => $attendancePercentage,
            'last_attended' => $lastAttended
        ];
    }

    /**
     * Get payment data for the student
     */
    private function getPaymentData($student)
    {
        try {
            // Get actual payment records from the database
            if (method_exists($student, 'payments')) {
                $paymentRecords = $student->payments()
                    ->where('status', 'completed')
                    ->orderBy('payment_date', 'desc')
                    ->get();
                
                // Add program name to each payment record
                $paymentRecords->each(function ($payment) use ($student) {
                    // Get the enrollment for this student at the time of payment
                    $enrollment = $student->enrollments()
                        ->where('created_at', '<=', $payment->payment_date)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    
                    if ($enrollment && $enrollment->program) {
                        $payment->program_name = $enrollment->program->name;
                    } else {
                        $payment->program_name = 'Program Payment';
                    }
                });
                
                $totalPaid = $paymentRecords->sum('amount');
                
                // Get the latest payment details
                $latestPayment = $paymentRecords->first();
                $paymentDate = $latestPayment ? \Carbon\Carbon::parse($latestPayment->payment_date)->format('M d, Y') : '--';
                $paymentMethod = $latestPayment ? ($latestPayment->payment_method ?? '--') : '--';
                
                return [
                    'total_paid' => $totalPaid,
                    'total_due' => 500, // Placeholder for now
                    'payment_date' => $paymentDate,
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'current', // Placeholder for now
                    'payment_records' => $paymentRecords
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error fetching payment data: ' . $e->getMessage());
        }
        
        // Fallback to placeholder data if there's an error
        return [
            'total_paid' => 1250,
            'total_due' => 500,
            'payment_date' => '--',
            'payment_method' => '--',
            'payment_status' => 'current',
            'payment_records' => collect([])
        ];
    }

    /**
     * Get certificate data for the student
     */
    private function getCertificateData($student)
    {
        try {
            // Get the latest enrollment
            $enrollment = $student->enrollments()
                ->with(['program'])
                ->latest()
                ->first();
            
            if (!$enrollment || !$enrollment->program) {
                return [
                    'total_certificates' => 0,
                    'eligible_certificates' => 0,
                    'is_eligible' => false,
                    'earned_certificates' => []
                ];
            }
            
            // Get attendance data
            $attendance = $this->getAttendanceData($student);
            
            // Check eligibility based on attendance (at least 80% attendance required)
            $isEligible = false;
            if ($attendance['total_sessions'] > 0 && $attendance['attendance_percentage'] >= 80) {
                $isEligible = true;
            }
            
            // Check if certificates already exist
            $earnedCertificates = [];
            if (method_exists($student, 'certificates')) {
                $earnedCertificates = $student->certificates()->get()->toArray();
            }
            
            return [
                'total_certificates' => count($earnedCertificates),
                'eligible_certificates' => $isEligible ? 1 : 0,
                'is_eligible' => $isEligible,
                'attendance_percentage' => $attendance['attendance_percentage'],
                'earned_certificates' => $earnedCertificates
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching certificate data: ' . $e->getMessage());
            
            // Fallback to placeholder data
            return [
                'total_certificates' => 0,
                'eligible_certificates' => 0,
                'is_eligible' => false,
                'attendance_percentage' => 0,
                'earned_certificates' => []
            ];
        }
    }

    /**
     * Get upcoming sessions for the student
     */
    private function getUpcomingSessions($student)
    {
        try {
            // Get the latest enrollment
            $enrollment = $student->enrollments()
                ->with(['program'])
                ->latest()
                ->first();

            if (!$enrollment || !$enrollment->program) {
                return [
                    'upcoming_sessions' => [],
                    'this_week_count' => 0
                ];
            }

            // Get all schedules for the program (since schedules are recurring weekly)
            $schedules = $enrollment->program->schedules()->get();

            // Count sessions this week (all schedules are considered weekly recurring)
            $thisWeekSessions = $schedules->count();

            return [
                'upcoming_sessions' => $schedules,
                'this_week_count' => $thisWeekSessions
            ];

        } catch (\Exception $e) {
            Log::error('Error fetching upcoming sessions: ' . $e->getMessage());

            // Fallback to placeholder data
            return [
                'upcoming_sessions' => [],
                'this_week_count' => 0
            ];
        }
    }

    /**
     * Get recent activities for the student
     */
    private function getRecentActivities($student)
    {
        $activities = collect();
        
        try {
            // Get the latest enrollment
            $enrollment = $student->enrollments()
                ->with(['program'])
                ->latest()
                ->first();
            
            if ($enrollment) {
                // Get recent attendance activities (last 30 days) - include both attended and scheduled sessions
                if (method_exists($enrollment, 'attendances')) {
                    $recentAttendances = $enrollment->attendances()
                        ->where('session_date', '>=', now()->subDays(30))
                        ->orderBy('session_date', 'desc')
                        ->get();
                    
                    foreach ($recentAttendances as $attendance) {
                        if ($attendance->status === 'present') {
                            $activities->push([
                                'type' => 'attendance',
                                'description' => 'Attended session on ' . \Carbon\Carbon::parse($attendance->session_date)->format('M d, Y'),
                                'date' => \Carbon\Carbon::parse($attendance->session_date)->diffForHumans(),
                                'timestamp' => $attendance->session_date
                            ]);
                        } else {
                            // Show scheduled sessions that haven't been attended yet
                            $activities->push([
                                'type' => 'session',
                                'description' => 'Session scheduled for ' . \Carbon\Carbon::parse($attendance->session_date)->format('M d, Y'),
                                'date' => \Carbon\Carbon::parse($attendance->session_date)->diffForHumans(),
                                'timestamp' => $attendance->session_date
                            ]);
                        }
                    }
                }
            }
            
            // Get recent payment activities (last 30 days)
            if (method_exists($student, 'payments')) {
                $recentPayments = $student->payments()
                    ->where('status', 'completed')
                    ->where('payment_date', '>=', now()->subDays(30))
                    ->orderBy('payment_date', 'desc')
                    ->get();
                
                // Enhanced Debug: Log payment retrieval with more details
                Log::info('Recent payments query executed', [
                    'student_id' => $student->id,
                    'student_email' => $student->email,
                    'payment_count' => $recentPayments->count(),
                    'payment_criteria' => [
                        'status' => 'completed',
                        'date_range' => 'last_30_days'
                    ],
                    'payments' => $recentPayments->map(function($payment) {
                        return [
                            'id' => $payment->id,
                            'amount' => $payment->amount,
                            'status' => $payment->status,
                            'payment_date' => $payment->payment_date,
                            'payment_method' => $payment->payment_method
                        ];
                    })->toArray()
                ]);
                
                foreach ($recentPayments as $payment) {
                    $activities->push([
                        'type' => 'payment',
                        'description' => 'Payment of $' . number_format($payment->amount) . ' processed successfully',
                        'date' => \Carbon\Carbon::parse($payment->payment_date)->diffForHumans(),
                        'timestamp' => $payment->payment_date
                    ]);
                }
            }
            
            // Get recent certificate activities (last 30 days)
            if (method_exists($student, 'certificates')) {
                $recentCertificates = $student->certificates()
                    ->where('issued_at', '>=', now()->subDays(30))
                    ->orderBy('issued_at', 'desc')
                    ->get();
                
                foreach ($recentCertificates as $certificate) {
                    $activities->push([
                        'type' => 'certificate',
                        'description' => 'Earned certificate for "' . ($certificate->program ?? 'Program') . '"',
                        'date' => \Carbon\Carbon::parse($certificate->issued_at)->diffForHumans(),
                        'timestamp' => $certificate->issued_at
                    ]);
                }
            }
            
            // Sort all activities by timestamp (most recent first) and take top 5
            $activities = $activities->sortByDesc('timestamp')->take(5);
            
            // Debug: Log final activities
            Log::info('Final recent activities', [
                'student_id' => $student->id,
                'activity_count' => $activities->count(),
                'activities' => $activities->toArray()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching recent activities: ' . $e->getMessage());
            
            // Return empty array if there's an error
            return [];
        }
        
        // If no recent activities found, return empty array
        if ($activities->isEmpty()) {
            Log::info('No recent activities found for student', ['student_id' => $student->id]);
            return [];
        }
        
        return $activities->values()->all();
    }

    /**
     * Check if the student has completed their payment
     */
    private function checkPaymentStatus($student)
    {
        try {
            // Check if the payments relationship exists and has completed payments
            if (method_exists($student, 'payments')) {
                $payments = $student->payments()->where('status', 'completed')->get();
                return $payments->isNotEmpty();
            }
            return false; // No payment relationship exists
        } catch (\Exception $e) {
            // Log the error and return false to prevent breaking the application
            Log::error('Payment status check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if the student has paid the registration fee for their program
     */
    public function checkRegistrationFeeStatus($student)
    {
        try {
            // Get the latest enrollment
            $enrollment = $student->enrollments()
                ->with(['program'])
                ->latest()
                ->first();

            if (!$enrollment || !$enrollment->program) {
                return false;
            }

            // Check if registration fee is required (greater than 0)
            if ($enrollment->program->registration_fee <= 0) {
                return true; // No registration fee required
            }

            // Check if registration fee has been paid
            return $enrollment->registration_fee_paid;
        } catch (\Exception $e) {
            Log::error('Registration fee status check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get total paid sessions from enrollment or by summing all completed payments' session_count
     */
    private function getTotalPaidSessions($student)
    {
        try {
            // Get the latest enrollment
            $enrollment = $student->enrollments()
                ->latest()
                ->first();
            
            // If enrollment has paid_sessions field, use it
            if ($enrollment && isset($enrollment->paid_sessions)) {
                return $enrollment->paid_sessions;
            }
            
            // Fallback: calculate from payments if paid_sessions field doesn't exist
            if (method_exists($student, 'payments')) {
                $paidSessions = $student->payments()
                    ->where('status', 'completed')
                    ->sum('session_count');
                return $paidSessions;
            }
            return 0; // No payment relationship exists
        } catch (\Exception $e) {
            Log::error('Error fetching total paid sessions: ' . $e->getMessage());
            return 0;
        }
    }
    public function attendance()
    {
        $student = Auth::user();
        $enrollment = $student->enrollments()
            ->with(['program'])
            ->latest()
            ->first();

        // Get additional data for dashboard
        $program = $enrollment->program ?? null;

        // Get attendance data - show limited data if registration fee not paid
        $attendance = $this->getAttendanceData($student);

        // Get total paid sessions by summing all completed payments
        $paidSessions = $this->getTotalPaidSessions($student);

        // Get instructors assigned to the student's program
        $instructors = [];
        if ($program) {
            $instructors = \App\Models\Schedule::where('program_id', $program->id)
                ->with('instructorUser') // Assuming a relation to User model for instructor
                ->get()
                ->map(function ($schedule) {
                    return $schedule->instructorUser->name ?? $schedule->instructor;
                })->unique()->values();
        }

        // Set flash message for enrollment status
        if ($enrollment) {
            $enrollmentStatusMessage = $this->getEnrollmentStatusMessage($enrollment);
            session()->flash('enrollmentStatus', $enrollmentStatusMessage);
        }

        return view('Student.attendance', compact(
            'student',
            'enrollment',
            'program',
            'attendance',
            'paidSessions',
            'instructors'
        ));
    }

    public function payment()
    {
        $student = Auth::user();
        $enrollment = $student->enrollments()
            ->with(['program'])
            ->latest()
            ->first();

        // Get additional data for dashboard
        $program = $enrollment->program ?? null;

        // Get payment data - show limited data if registration fee not paid
        $payments = $this->getPaymentData($student);

        // Set flash message for enrollment status
        if ($enrollment) {
            $enrollmentStatusMessage = $this->getEnrollmentStatusMessage($enrollment);
            session()->flash('enrollmentStatus', $enrollmentStatusMessage);
        }

        return view('Student.payment', compact(
            'student',
            'enrollment',
            'program',
            'payments'
        ));
    }

    public function ajaxAttendance()
    {
        $student = Auth::user();
        $attendance = $this->getAttendanceData($student);
        $paidSessions = $this->getTotalPaidSessions($student);

        // Get enrollment and program
        $enrollment = $student->enrollments()
            ->with(['program'])
            ->latest()
            ->first();

        $program = $enrollment->program ?? null;

        // Get instructors assigned to the student's program
        $instructors = [];
        if ($program) {
            $instructors = \App\Models\Schedule::where('program_id', $program->id)
                ->with('instructorUser') // Assuming a relation to User model for instructor
                ->get()
                ->map(function ($schedule) {
                    return $schedule->instructorUser->name ?? $schedule->instructor;
                })->unique()->values();
        }

        return response()->json([
            'success' => true,
            'attended_sessions' => $attendance['attended_sessions'],
            'total_sessions' => $attendance['total_sessions'],
            'paid_sessions' => $paidSessions,
            'instructors' => $instructors,
        ]);
    }

    public function certificate()
    {
        $student = Auth::user();
        $enrollment = $student->enrollments()
            ->with(['program'])
            ->latest()
            ->first();

        // Get additional data for dashboard
        $program = $enrollment->program ?? null;

        // Get certificates data - show limited data if registration fee not paid
        $certificates = $this->getCertificateData($student);

        // Set flash message for enrollment status
        if ($enrollment) {
            $enrollmentStatusMessage = $this->getEnrollmentStatusMessage($enrollment);
            session()->flash('enrollmentStatus', $enrollmentStatusMessage);
        }

        return view('Student.certificate', compact(
            'student',
            'enrollment',
            'program',
            'certificates'
        ));
    }

    /**
     * Update the authenticated student's profile photo.
     */
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|max:2048', // max 2MB
        ]);

        $student = Auth::user();

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile_photos', 'public');

            // Update student's photo path
            $student->photo = $path;
            $student->save();

            return response()->json(['success' => true, 'path' => $path]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded']);
    }

    /**
     * Handle payment checkout (redirect to GCash or payment gateway).
     */
    public function paymentCheckout(Request $request)
    {
        $student = Auth::user();
        $amount = $request->get('amount', 0);
        $sessionCount = $request->get('session_count', 1);

        // Here you would typically integrate with GCash API or redirect to payment gateway
        // For now, we'll simulate a redirect or return a response

        // You can redirect to GCash payment page or handle it here
        // For demonstration, we'll return a JSON response or redirect

        return response()->json([
            'success' => true,
            'message' => 'Redirecting to GCash payment...',
            'amount' => $amount,
            'session_count' => $sessionCount
        ]);
    }

    /**
     * Process direct payment (for GCash or other payment methods).
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'email' => 'required|email',
            'session_count' => 'required|integer|min:1',
            'payment_type' => 'required|in:session,registration',
        ]);

        $student = Auth::user();
        $amount = $request->amount;
        $sessionCount = $request->session_count;
        $paymentType = $request->payment_type;

        try {
            // Get the latest enrollment
            $enrollment = $student->enrollments()
                ->with(['program'])
                ->latest()
                ->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No enrollment found.',
                ], 400);
            }

            // Validate payment type and amount
            if ($paymentType === 'registration') {
                if (!$enrollment->program || $enrollment->program->registration_fee <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No registration fee required for this program.',
                    ], 400);
                }

                if ($enrollment->registration_fee_paid) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Registration fee has already been paid.',
                    ], 400);
                }

                if ($amount != $enrollment->program->registration_fee) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment amount does not match the registration fee.',
                    ], 400);
                }
            }

            // Here you would integrate with GCash API to process the payment
            // For now, we'll simulate a successful payment

            // Create a payment record
            if (method_exists($student, 'payments')) {
                $student->payments()->create([
                    'amount' => $amount,
                    'session_count' => $paymentType === 'session' ? $sessionCount : 0,
                    'payment_method' => 'GCash',
                    'status' => 'completed',
                    'payment_date' => now(),
                    'transaction_id' => 'GCASH-' . time() . '-' . rand(1000, 9999),
                    'payment_type' => $paymentType,
                ]);
            }

            // Update enrollment based on payment type
            if ($paymentType === 'registration') {
                $enrollment->registration_fee_paid = true;
                $enrollment->save();
            } elseif ($paymentType === 'session' && isset($enrollment->paid_sessions)) {
                $enrollment->paid_sessions += $sessionCount;
                $enrollment->save();
            }

            $message = $paymentType === 'registration'
                ? 'Registration fee payment processed successfully!'
                : 'Session payment processed successfully!';

            return response()->json([
                'success' => true,
                'message' => $message,
                'transaction_id' => 'GCASH-' . time() . '-' . rand(1000, 9999),
            ]);

        } catch (\Exception $e) {
            Log::error('Payment processing failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Payment failed. Please try again.',
            ], 500);
        }
    }
}
