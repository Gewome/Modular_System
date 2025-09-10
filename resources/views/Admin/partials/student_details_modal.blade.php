<!-- Student Details Modal -->
<div id="studentDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Student Details</h3>
                <button id="closeStudentDetailsModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

        <!-- Loading State -->
        <div id="loadingState" class="flex flex-col items-center justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-600">Loading student details...</p>
        </div>

        <!-- Error State -->
        <div id="errorState" class="hidden flex flex-col items-center justify-center py-12">
            <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
            <p class="text-gray-600 text-center" id="errorMessage">An error occurred while loading student details.</p>
        </div>

        <!-- Student Details Content -->
        <div id="studentDetailsContent" class="hidden">
            <!-- Student Information -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-4 text-gray-700">Student Information</h4>
                <div id="studentInfoContainer" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Student Name -->
                    <div class="bg-white border border-gray-300 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user text-blue-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Student Name</label>
                                <p id="studentName" class="text-lg font-semibold text-gray-900">Loading...</p>
                                <p id="studentProgram" class="text-md text-gray-700 mt-1">Loading...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="bg-white border border-gray-300 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <p class="text-lg text-gray-900" id="studentAddress">Loading...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="bg-white border border-gray-300 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-phone text-purple-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <p class="text-lg text-gray-900" id="studentPhone">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-4 text-gray-700">Session Details</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg shadow-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Attended</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OR Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Number</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceTableContainer" class="bg-white divide-y divide-gray-200">
                            <!-- Hardcoded attendance data -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">1</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Monday</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">09:00 AM</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">₱500.00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">OR-2024-001</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">TXN-123456789</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">2</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-22</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Monday</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">09:00 AM</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">₱500.00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">OR-2024-002</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">TXN-123456790</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">3</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-29</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Monday</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">09:00 AM</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">₱500.00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">OR-2024-003</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">TXN-123456791</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">4</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-02-05</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Monday</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">09:00 AM</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">₱500.00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">OR-2024-004</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">TXN-123456792</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Instructor Information -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-4 text-gray-700">Instructor Information</h4>
                <div class="bg-white border border-gray-300 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chalkboard-teacher text-indigo-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instructor's Name</label>
                            <p class="text-lg font-semibold text-gray-900" id="instructorName">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
