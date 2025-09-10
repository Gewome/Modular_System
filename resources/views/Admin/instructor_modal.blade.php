<!-- Add Instructor Modal -->
<div id="addInstructorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add New Instructor</h3>
                <button id="closeInstructorModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="addInstructorForm">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="instructorFirstName">
                            First Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="instructorFirstName" name="first_name" type="text" placeholder="First name" required>
                        <span class="text-red-500 text-xs italic hidden" id="instructorFirstNameError"></span>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="instructorLastName">
                            Last Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="instructorLastName" name="last_name" type="text" placeholder="Last name" required>
                        <span class="text-red-500 text-xs italic hidden" id="instructorLastNameError"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="instructorMiddleName">
                            Middle Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="instructorMiddleName" name="middle_name" type="text" placeholder="Middle name">
                        <span class="text-red-500 text-xs italic hidden" id="instructorMiddleNameError"></span>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="instructorSuffixName">
                            Suffix Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="instructorSuffixName" name="suffix_name" type="text" placeholder="Suffix (Jr, Sr, etc.)">
                        <span class="text-red-500 text-xs italic hidden" id="instructorSuffixNameError"></span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="instructorProgramHandled">
                        Program Handled
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="instructorProgramHandled" name="program_handled" required>
                        <option value="">Select a program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-red-500 text-xs italic hidden" id="instructorProgramHandledError"></span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="instructorEmail">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="instructorEmail" name="email" type="email" placeholder="Email" required>
                    <span class="text-red-500 text-xs italic hidden" id="instructorEmailError"></span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="instructorBirthdate">
                        Birthdate
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="instructorBirthdate" name="birthdate" type="date" required>
                    <span class="text-red-500 text-xs italic hidden" id="instructorBirthdateError"></span>
                    <span class="text-red-500 text-xs italic hidden" id="instructorPasswordError"></span>
                    <p class="text-xs text-gray-500 mt-1">Password will be automatically set to the birthdate (YYYY-MM-DD format)</p>
                </div>

                <!-- Auto-generated fields (hidden) -->
                <input type="hidden" id="instructorUsername" name="username">
                <input type="hidden" id="instructorPassword" name="password">
                <input type="hidden" name="role" value="instructor">
                <p class="text-xs text-gray-500 mt-1">Username will be automatically generated as: firstname + lastname + suffix (all lowercase, no spaces)</p>
                <div class="flex items-center justify-end gap-2">
                    <button type="button" id="cancelInstructorBtn" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancel
                    </button>
                    <button type="submit" id="saveInstructorBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <i class="fas fa-save mr-2"></i>Save Instructor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal for Instructor Creation -->
<div id="instructor-success-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <button onclick="closeInstructorSuccessModal()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl font-bold">
            &times;
        </button>
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <i class="fas fa-check text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Instructor Account Created Successfully!</h3>
            <p class="text-sm text-gray-600 mb-4">The instructor account has been created and is ready to use. Here are the login credentials:</p>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Username:</span>
                        <span id="instructor-success-username" class="text-sm font-mono bg-white px-2 py-1 rounded border text-gray-900"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Password:</span>
                        <span id="instructor-success-password" class="text-sm font-mono bg-white px-2 py-1 rounded border text-gray-900"></span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                <button id="copyInstructorCredentialsBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-copy mr-2"></i>Copy Credentials
                </button>
                <button onclick="closeInstructorSuccessModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
