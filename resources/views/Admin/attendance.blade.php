<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        /* For Webkit browsers (Chrome, Safari) */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .sidebar-collapsed {
            width: 80px !important;
        }

        .sidebar-collapsed #toggleSidebar .collapse-icon {
            display: none;
        }

        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .logo-text,
        .sidebar-collapsed .user-profile .user-details {
            display: none;
        }

        .sidebar-collapsed .nav-item {
            justify-content: center;
        }

        .content-area {
            transition: all 0.3s ease;
            margin-left: 0.5rem;
        }

        .active-nav {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid white;
        }

        /* Session tabs horizontal scrolling */
        .session-tabs-container {
            overflow-x: auto;
            overflow-y: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(59, 130, 246, 0.3) transparent;
        }

        .session-tabs-container::-webkit-scrollbar {
            height: 4px;
        }

        .session-tabs-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .session-tabs-container::-webkit-scrollbar-thumb {
            background-color: rgba(59, 130, 246, 0.3);
            border-radius: 2px;
        }

        .session-tabs-container::-webkit-scrollbar-thumb:hover {
            background-color: rgba(59, 130, 246, 0.5);
        }

        .session-tabs-nav {
            display: flex;
            flex-wrap: nowrap;
            min-width: max-content;
            padding: 0 1rem;
        }

        .session-tab {
            flex-shrink: 0;
            white-space: nowrap;
        }

        /* Scroll hint indicators */
        .scroll-hint-left,
        .scroll-hint-right {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 20px;
            background: linear-gradient(to right, rgba(249, 250, 251, 0.9), transparent);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
        }

        .scroll-hint-right {
            right: 0;
            background: linear-gradient(to left, rgba(249, 250, 251, 0.9), transparent);
        }

        .session-tabs-container.has-scroll-left .scroll-hint-left {
            opacity: 1;
        }

        .session-tabs-container.has-scroll-right .scroll-hint-right {
            opacity: 1;
        }

        /* Floating notification styles */
        .floating-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
            max-width: 500px;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.05);
            animation: slideInRight 0.3s ease-out;
            font-weight: 500;
            font-size: 14px;
            line-height: 1.4;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .floating-notification.success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-color: rgba(16, 185, 129, 0.3);
        }

        .floating-notification.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .floating-notification.fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .notification-icon {
            margin-right: 12px;
            font-size: 16px;
        }

        .notification-close {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
            font-size: 12px;
        }

        .notification-close:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 72px !important;
            }

            .nav-text,
            .logo-text {
                display: none;
            }

            .nav-item {
                justify-content: center;
            }

            .floating-notification {
                left: 20px;
                right: 20px;
                min-width: auto;
                max-width: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-900 text-white w-56 flex flex-col">
            @include('Admin.partials.navigation')
        </div>

        <!-- Main Content -->
        <div class="content-area flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow-md p-4 flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center space-x-4">
                    <button id="mobileMenuButton" class="text-gray-500 hover:text-gray-700 md:hidden transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-gray-800 bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">
                        Attendance Management
                    </h2>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="border-l h-8 border-gray-200"></div>
                    <a href="{{ route('admin.attendance.records') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        View Records
                    </a>
                    <div class="border-l h-8 border-gray-200"></div>
                    <div class="relative group">
                        <button id="adminDropdown" class="flex items-center focus:outline-none space-x-2">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow">
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Profile Photo" class="w-9 h-9 rounded-full object-cover">
                                @else
                                    <i class="fas fa-user text-white"></i>
                                @endif
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="#" onclick="openProfileModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="#" id="logoutButton" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Content -->
            <div class="p-6">
                <!-- Floating Notifications -->
                @if(session('success'))
                    <div id="floatingSuccess" class="floating-notification success" style="display:none;">
                        <span class="notification-icon"><i class="fas fa-check-circle"></i></span>
                        <span id="floatingSuccessMessage">{{ session('success') }}</span>
                        <button class="notification-close" onclick="closeFloatingNotification('floatingSuccess')">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div id="floatingError" class="floating-notification error" style="display:none;">
                        <span class="notification-icon"><i class="fas fa-exclamation-circle"></i></span>
                        <span id="floatingErrorMessage">{{ session('error') }}</span>
                        <button class="notification-close" onclick="closeFloatingNotification('floatingError')">&times;</button>
                    </div>
                @endif

                <!-- Modal for Completed Sessions -->
                <div id="completedSessionsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
                        <h3 class="text-lg font-semibold mb-4" id="completedSessionsMessage"></h3>
                        <div class="flex justify-end space-x-4">
                            <button id="viewAllCertificatesBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                View All
                            </button>
                            <button id="closeModalBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400">
                                Close
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Program Tabs -->
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px">
                            @foreach($programs as $index => $program)
                            <button class="program-tab py-4 px-6 text-center border-b-2 font-medium text-sm
                                {{ $index === 0 ? 'active-tab border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}" data-program-id="{{ $program->id }}" data-duration="{{ $program->duration }}">
                                {{ $program->name }}
                            </button>
                            @endforeach
                        </nav>
                    </div>

                        <!-- Session Tabs -->
                        <div class="border-b border-gray-200 bg-gray-50 relative">
                            <div class="scroll-hint-left"></div>
                            <div class="scroll-hint-right"></div>
                            <div class="session-tabs-container">
                                <nav class="session-tabs-nav" id="sessionTabs" data-session1-count="{{ $session1StudentCount ?? 0 }}">
                                    @php
                                    $sessionCount = 1; // Default fallback
                                    if ($programs->first()) {
                                        $durationString = $programs->first()->duration;
                                        if (is_numeric($durationString)) {
                                            $sessionCount = (int)$durationString;
                                        } else {
                                            // Try to extract number from string like "10 weeks" or "2 months"
                                            preg_match('/(\d+)/', $durationString, $matches);
                                            if (!empty($matches[1])) {
                                                $sessionCount = (int)$matches[1];
                                            }
                                        }
                                        $sessionCount = max(1, $sessionCount);
                                    }
                                    @endphp
                                    @for($i = 1; $i <= $sessionCount; $i++)
                                    <button class="session-tab {{ $i === $currentSession ? 'active-tab border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-3 px-4 text-center border-b-2 font-medium text-xs" data-session="{{ $i }}">
                                        Session {{ $i }}
                                    </button>
                                    @endfor
                                    <button class="session-tab {{ $currentSession === $sessionCount + 1 ? 'active-tab border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-3 px-4 text-center border-b-2 font-medium text-xs" data-session="{{ $sessionCount + 1 }}">
                                        Extension
                                    </button>
                                </nav>
                            </div>
                        </div>

                        <!-- Attendance Table -->
                        <form id="attendanceForm" action="{{ route('admin.attendance.save') }}" method="POST">
                            @csrf
                            <input type="hidden" name="session_number" id="currentSession" value="{{ $currentSession }}">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Student Name
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Program
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Reference Number
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                OR Number
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Payment Status
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Session Eligibility
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mark as Present
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php
                                            $hasEligibleStudents = false;
                                        @endphp
                                        @foreach($enrolledStudents as $student)
                                        @php
                                            $enrollment = $student->enrollments->first();
                                            $eligibility = $student->session_eligibility;
                                            $completedSessions = $student->payments->where('status', 'completed')->count();

                                            // Skip rendering if user status is inactive
                                            if ($student->status === 'inactive') {
                                                continue;
                                            }

                                            // Determine totalSessions based on payment status or OR encoded for current session
                                            $totalSessions = 0;
                                            $isExtensionSession = ($currentSession === $sessionCount + 1);

                                            if ($isExtensionSession) {
                                                // For Extension session, show as Extension 1 of 1 if paid, or Extension 1 of 0 if not paid
                                                $totalSessions = ($eligibility['payment_status'] === 'paid' || !empty($enrollment->or_number)) ? 1 : 0;
                                                $currentSessionDisplay = 1; // Always show as "Extension 1"
                                            } elseif ($currentSession == 1) {
                                                if ($eligibility['payment_status'] === 'paid' || !empty($enrollment->or_number)) {
                                                    $totalSessions = 1;
                                                } else {
                                                    $totalSessions = 0;
                                                }
                                                $currentSessionDisplay = $completedSessions >= $currentSession ? $currentSession + 1 : $currentSession;
                                            } else {
                                                // For sessions > 1, totalSessions is currentSession - 1 if paid or OR encoded for previous session
                                                $previousSessionPaid = false;
                                                if ($eligibility['payment_status'] === 'paid' || !empty($enrollment->or_number)) {
                                                    $previousSessionPaid = true;
                                                }
                                                $totalSessions = $previousSessionPaid ? $currentSession - 1 : $currentSession - 2;
                                                if ($totalSessions < 0) {
                                                    $totalSessions = 0;
                                                }
                                                $currentSessionDisplay = $completedSessions >= $currentSession ? $currentSession + 1 : $currentSession;
                                            }

                                            // Determine payment status text for display
                                            $paymentStatusText = '';
                                            if ($eligibility['payment_status'] === 'paid' || !empty($enrollment->or_number)) {
                                                $paymentStatusText = 'Paid';
                                            }

                                            if ($enrollment && $eligibility['eligible']) {
                                                $hasEligibleStudents = true;
                                            }
                                        @endphp
                                        @if($enrollment && $eligibility['eligible'])
                                        <tr class="hover:bg-gray-50" data-program-id="{{ $enrollment->program_id }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden">
                                                        @if($enrollment->photo)
                                                        <img src="{{ asset('storage/' . $enrollment->photo) }}" class="h-10 w-10 object-cover rounded-full" />
                                                        @elseif($enrollment->user && $enrollment->user->photo)
                                                        <img src="{{ asset('storage/' . $enrollment->user->photo) }}" class="h-10 w-10 object-cover rounded-full" />
                                                        @else
                                                        <div class="h-10 w-10 bg-blue-100 flex items-center justify-center rounded-full">
                                                            <i class="fas fa-user text-blue-600"></i>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->program->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    @php
                                                        $referenceNumber = null;
                                                        $referenceType = null;

                                                        // Only show Transaction ID as Reference Number for sessions that are actually paid for
                                                        $payment = $student->payments->first();
                                                        if ($payment && $payment->transaction_id && $currentSession <= $eligibility['paid_sessions']) {
                                                            $referenceNumber = $payment->transaction_id;
                                                            $referenceType = 'Transaction ID';
                                                        }
                                                    @endphp
                                                    <div class="flex flex-col">
                                                        <span class="text-sm text-gray-900">{{ $referenceNumber ?? 'Auto-generated on payment' }}</span>
                                                        @if($referenceNumber)
                                                            <span class="text-xs text-gray-500">{{ $referenceType }}</span>
                                                        @else
                                                            <span class="text-xs text-gray-400 italic">Auto-generated on payment</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    @php
                                                        $orNumber = $enrollment->or_number;
                                                        $hasReferenceNumber = $payment && $payment->transaction_id && $currentSession <= $eligibility['paid_sessions'];
                                                    @endphp
                                                    <div class="flex flex-col">
                                                        <span id="or-number-{{ $enrollment->id }}" class="text-sm text-gray-900">{{ $orNumber ?? 'Not set' }}</span>
                                                        @if($orNumber)
                                                            <span class="text-xs text-gray-500">OR Number</span>
                                                        @endif
                                                    </div>
                                                    @if(!$hasReferenceNumber)
                                                    <button type="button" onclick="editOrNumber({{ $enrollment->id }}, '{{ $orNumber ?? '' }}')" class="text-blue-600 hover:text-blue-800 text-xs">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $orNumber = $enrollment->or_number;
                                                    $referenceNumber = null;
                                                    $payment = $student->payments->first();
                                                    if ($payment && $payment->transaction_id && $currentSession <= $eligibility['paid_sessions']) {
                                                        $referenceNumber = $payment->transaction_id;
                                                    }
                                                    $hasPaymentVerification = !empty($referenceNumber) || !empty($orNumber);
                                                @endphp
                                                @if($eligibility['payment_status'] === 'paid' && $hasPaymentVerification)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                                @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Payment Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($isExtensionSession)
                                                    Extension {{ $currentSessionDisplay }} of {{ $totalSessions }}{{ $paymentStatusText ? ' ' . $paymentStatusText : '' }}
                                                @else
                                                    Session {{ $currentSessionDisplay }} of {{ $totalSessions }}{{ $paymentStatusText ? ' ' . $paymentStatusText : '' }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($referenceNumber || $orNumber)
                                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="attendance[]" value="{{ $student->id }}">
                                                    @if($referenceNumber)
                                                    <span class="text-xs text-gray-400 ml-2">Online payment verified</span>
                                                    @else
                                                    <span class="text-xs text-gray-400 ml-2">Manual payment verified</span>
                                                    @endif
                                                @else
                                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-gray-400 opacity-50 cursor-not-allowed" name="attendance[]" value="{{ $student->id }}" disabled>
                                                    <span class="text-xs text-gray-400 ml-2">Payment verification required</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach

                                        @if(!$hasEligibleStudents)
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center justify-center">
                                                    <i class="fas fa-users-slash text-4xl text-gray-300 mb-4"></i>
                                                    <p class="text-lg font-medium text-gray-400">No students available for attendance</p>
                                                    <p class="text-sm text-gray-500 mt-1">Students may not be enrolled, eligible, or have paid for this session.</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- Save Button -->
                            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                                <div id="dateDisplay" class="text-sm text-gray-500 hidden">
                                    Today's Date: <span id="currentDate"></span>
                                </div>
                                <button type="submit" id="saveAttendanceBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hidden">
                                    Save Attendance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Function to display today's date
            function displayDate() {
                const dateDisplay = document.getElementById('dateDisplay');
                const currentDateSpan = document.getElementById('currentDate');
                const today = new Date();
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                currentDateSpan.textContent = today.toLocaleDateString(undefined, options);
                dateDisplay.classList.remove('hidden');
            }

            // Unified function to generate session tabs and highlight current session
            function generateSessionTabs(sessionCount, currentSession) {
                const sessionTabs = document.getElementById('sessionTabs');
                sessionTabs.innerHTML = '';
                for (let i = 1; i <= sessionCount; i++) {
                    const isActive = (i === currentSession);
                    const sessionText = `Session ${i}`;
                    sessionTabs.innerHTML += `<button class="session-tab ${isActive ? 'active-tab border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'} py-3 px-4 text-center border-b-2 font-medium text-xs" data-session="${i}">${sessionText}</button>`;
                }
                // Add Extension tab
                const isExtensionActive = (currentSession === sessionCount + 1);
                const extensionText = 'Extension';
                sessionTabs.innerHTML += `<button class="session-tab ${isExtensionActive ? 'active-tab border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'} py-3 px-4 text-center border-b-2 font-medium text-xs" data-session="${sessionCount + 1}">${extensionText}</button>`;
            }

            // Function to update scroll hints
            function updateScrollHints() {
                const container = document.querySelector('.session-tabs-container');
                if (!container) return;

                const scrollLeft = container.scrollLeft;
                const scrollWidth = container.scrollWidth;
                const clientWidth = container.clientWidth;

                // Remove existing classes
                container.classList.remove('has-scroll-left', 'has-scroll-right');

                // Add classes based on scroll position
                if (scrollLeft > 0) {
                    container.classList.add('has-scroll-left');
                }
                if (scrollLeft < scrollWidth - clientWidth) {
                    container.classList.add('has-scroll-right');
                }
            }

            // Function to show floating notification
            function showFloatingNotification(type) {
                const notification = document.getElementById('floating' + type.charAt(0).toUpperCase() + type.slice(1));
                if (notification) {
                    notification.style.display = 'block';

                    // Auto-hide after 3 seconds
                    setTimeout(() => {
                        closeFloatingNotification('floating' + type.charAt(0).toUpperCase() + type.slice(1));
                    }, 3000);
                }
            }

            // Function to close floating notification
            function closeFloatingNotification(notificationId) {
                const notification = document.getElementById(notificationId);
                if (notification) {
                    notification.classList.add('fade-out');
                    setTimeout(() => {
                        notification.style.display = 'none';
                        notification.classList.remove('fade-out');
                    }, 500);
                }
            }

            // Function to edit reference number inline
            function editReferenceNumber(enrollmentId, currentValue, referenceType) {
                const spanElement = document.getElementById('reference-number-' + enrollmentId);
                const originalText = spanElement.textContent;

                // Create input field
                const input = document.createElement('input');
                input.type = 'text';
                input.value = currentValue || '';
                input.className = 'px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
                input.style.width = '120px';

                // Create save button
                const saveBtn = document.createElement('button');
                saveBtn.innerHTML = '<i class="fas fa-check text-green-600"></i>';
                saveBtn.className = 'ml-2 px-2 py-1 text-sm hover:bg-green-50 rounded';
                saveBtn.onclick = function() {
                    saveReferenceNumber(enrollmentId, input.value, referenceType);
                };

                // Create cancel button
                const cancelBtn = document.createElement('button');
                cancelBtn.innerHTML = '<i class="fas fa-times text-red-600"></i>';
                cancelBtn.className = 'ml-1 px-2 py-1 text-sm hover:bg-red-50 rounded';
                cancelBtn.onclick = function() {
                    spanElement.innerHTML = originalText;
                    const editBtn = document.createElement('button');
                    editBtn.type = 'button';
                    editBtn.onclick = function() { editReferenceNumber(enrollmentId, originalText === 'Not set' ? '' : originalText, referenceType); };
                    editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                    editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                    spanElement.appendChild(editBtn);
                };

                // Replace span content
                spanElement.innerHTML = '';
                spanElement.appendChild(input);
                spanElement.appendChild(saveBtn);
                spanElement.appendChild(cancelBtn);

                // Focus on input
                input.focus();
                input.select();

                // Handle Enter key
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        saveReferenceNumber(enrollmentId, input.value, referenceType);
                    } else if (e.key === 'Escape') {
                        cancelBtn.click();
                    }
                });
            }

            // Function to save reference number
            function saveReferenceNumber(enrollmentId, newValue, referenceType) {
                const spanElement = document.getElementById('reference-number-' + enrollmentId);

                // Show loading state
                spanElement.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-600"></i> Saving...';

                // Send AJAX request
                fetch('/admin/enrollments/' + enrollmentId + '/update-or-number', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        or_number: newValue
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update display
                        const displayText = newValue || 'Not set';
                        spanElement.innerHTML = displayText;

                        // Add edit button back
                        const editBtn = document.createElement('button');
                        editBtn.type = 'button';
                        editBtn.onclick = function() { editReferenceNumber(enrollmentId, newValue, referenceType); };
                        editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                        spanElement.appendChild(editBtn);

                        // Show success notification
                        showNotification('Reference number updated successfully!', 'success');

                        // Refresh the page to update student eligibility
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error
                        spanElement.innerHTML = data.message || 'Error updating reference number';
                        setTimeout(() => {
                            const originalValue = data.original_value || 'Not set';
                            spanElement.innerHTML = originalValue;
                            const editBtn = document.createElement('button');
                            editBtn.type = 'button';
                            editBtn.onclick = function() { editReferenceNumber(enrollmentId, originalValue === 'Not set' ? '' : originalValue, referenceType); };
                            editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                            editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                            spanElement.appendChild(editBtn);
                        }, 2000);
                        showNotification('Failed to update reference number', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    spanElement.innerHTML = 'Error updating reference number';
                    setTimeout(() => {
                        const originalValue = newValue || 'Not set';
                        spanElement.innerHTML = originalValue;
                        const editBtn = document.createElement('button');
                        editBtn.type = 'button';
                        editBtn.onclick = function() { editReferenceNumber(enrollmentId, originalValue === 'Not set' ? '' : originalValue, referenceType); };
                        editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                        spanElement.appendChild(editBtn);
                    }, 2000);
                    showNotification('Network error occurred', 'error');
                });
            }

            // Function to edit OR number inline
            function editOrNumber(enrollmentId, currentValue) {
                const spanElement = document.getElementById('or-number-' + enrollmentId);
                const originalText = spanElement.textContent;

                // Create input field
                const input = document.createElement('input');
                input.type = 'text';
                input.value = currentValue || '';
                input.className = 'px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
                input.style.width = '120px';

                // Create save button
                const saveBtn = document.createElement('button');
                saveBtn.innerHTML = '<i class="fas fa-check text-green-600"></i>';
                saveBtn.className = 'ml-2 px-2 py-1 text-sm hover:bg-green-50 rounded';
                saveBtn.onclick = function() {
                    saveOrNumber(enrollmentId, input.value);
                };

                // Create cancel button
                const cancelBtn = document.createElement('button');
                cancelBtn.innerHTML = '<i class="fas fa-times text-red-600"></i>';
                cancelBtn.className = 'ml-1 px-2 py-1 text-sm hover:bg-red-50 rounded';
                cancelBtn.onclick = function() {
                    spanElement.innerHTML = originalText;
                    const editBtn = document.createElement('button');
                    editBtn.type = 'button';
                    editBtn.onclick = function() { editOrNumber(enrollmentId, originalText === 'Not set' ? '' : originalText); };
                    editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                    editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                    spanElement.appendChild(editBtn);
                };

                // Replace span content
                spanElement.innerHTML = '';
                spanElement.appendChild(input);
                spanElement.appendChild(saveBtn);
                spanElement.appendChild(cancelBtn);

                // Focus on input
                input.focus();
                input.select();

                // Handle Enter key
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        saveOrNumber(enrollmentId, input.value);
                    } else if (e.key === 'Escape') {
                        cancelBtn.click();
                    }
                });
            }

            // Function to save OR number
            function saveOrNumber(enrollmentId, newValue) {
                const spanElement = document.getElementById('or-number-' + enrollmentId);

                // Show loading state
                spanElement.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-600"></i> Saving...';

                // Send AJAX request
                fetch('/admin/enrollments/' + enrollmentId + '/update-or-number', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        or_number: newValue
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update display
                        const displayText = newValue || 'Not set';
                        spanElement.innerHTML = displayText;

                        // Add edit button back
                        const editBtn = document.createElement('button');
                        editBtn.type = 'button';
                        editBtn.onclick = function() { editOrNumber(enrollmentId, newValue); };
                        editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                        spanElement.appendChild(editBtn);

                        // Show success notification
                        showNotification('OR number updated successfully!', 'success');

                        // Refresh the page to update student eligibility
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error
                        spanElement.innerHTML = data.message || 'Error updating OR number';
                        setTimeout(() => {
                            const originalValue = data.original_value || 'Not set';
                            spanElement.innerHTML = originalValue;
                            const editBtn = document.createElement('button');
                            editBtn.type = 'button';
                            editBtn.onclick = function() { editOrNumber(enrollmentId, originalValue === 'Not set' ? '' : originalValue); };
                            editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                            editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                            spanElement.appendChild(editBtn);
                        }, 2000);
                        showNotification('Failed to update OR number', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    spanElement.innerHTML = 'Error updating OR number';
                    setTimeout(() => {
                        const originalValue = newValue || 'Not set';
                        spanElement.innerHTML = originalValue;
                        const editBtn = document.createElement('button');
                        editBtn.type = 'button';
                        editBtn.onclick = function() { editOrNumber(enrollmentId, originalValue === 'Not set' ? '' : originalValue); };
                        editBtn.className = 'text-blue-600 hover:text-blue-800 text-xs ml-2';
                        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                        spanElement.appendChild(editBtn);
                    }, 2000);
                    showNotification('Network error occurred', 'error');
                });
            }

            // Function to show notification
            function showNotification(message, type) {
                // Remove existing notifications
                const existingNotifications = document.querySelectorAll('.custom-notification');
                existingNotifications.forEach(notification => notification.remove());

                // Create notification element
                const notification = document.createElement('div');
                notification.className = `custom-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;

                // Add to page
                document.body.appendChild(notification);

                // Auto remove after 3 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 3000);
            }

            // Tab switching functionality for Program Tabs
            document.querySelectorAll('.program-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all program tabs
                    document.querySelectorAll('.program-tab').forEach(t => {
                        t.classList.remove('active-tab', 'border-blue-500', 'text-blue-600');
                        t.classList.add('border-transparent', 'text-gray-500');
                    });

                    // Add active class to clicked tab
                    this.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    // Get selected program ID and duration
                    const selectedProgramId = this.getAttribute('data-program-id');
                    let sessionCount = 1; // Default fallback
                    const durationString = this.getAttribute('data-duration');

                    if (durationString && !isNaN(durationString)) {
                        sessionCount = parseInt(durationString);
                    } else if (durationString) {
                        // Try to extract number from string like "10 weeks" or "2 months"
                        const matches = durationString.match(/(\d+)/);
                        if (matches && matches[1]) {
                            sessionCount = parseInt(matches[1]);
                        }
                    }
                    sessionCount = Math.max(1, sessionCount);

                    // Store selected program in localStorage
                    localStorage.setItem('selectedProgramId', selectedProgramId);

                    // Show/hide table rows based on selected program
                    document.querySelectorAll('tr[data-program-id]').forEach(row => {
                        if (row.getAttribute('data-program-id') === selectedProgramId) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Get current session from URL or default to 1
                    const pathParts = window.location.pathname.split('/');
                    let currentSession = 1;
                    if (pathParts.length > 3 && !isNaN(pathParts[3])) {
                        currentSession = parseInt(pathParts[3]);
                    } else if (pathParts.length > 2 && !isNaN(pathParts[2])) {
                        currentSession = parseInt(pathParts[2]);
                    }

                // If current session exceeds sessionCount + 1, reset to 1
                // But allow Extension session (sessionCount + 1) to be valid
                if (currentSession > sessionCount + 1) currentSession = 1;

                    // Generate session tabs with current session highlighted
                    generateSessionTabs(sessionCount, currentSession);

                    // Update scroll hints after generating tabs
                    setTimeout(updateScrollHints, 100);
                });
            });

            // Single click event handler for session tabs using event delegation
            document.getElementById('sessionTabs').addEventListener('click', function(e) {
                if (e.target.classList.contains('session-tab')) {
                    document.querySelectorAll('.session-tab').forEach(t => {
                        t.classList.remove('active-tab', 'border-blue-500', 'text-blue-600');
                        t.classList.add('border-transparent', 'text-gray-500');
                    });
                    e.target.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                    e.target.classList.remove('border-transparent', 'text-gray-500');

                    // Update current session number and reload page
                    const sessionNumber = e.target.getAttribute('data-session');
                    if (sessionNumber) {
                        document.getElementById('currentSession').value = sessionNumber;

                        // Reload page with session parameter using route format
                        const baseUrl = window.location.origin + '/admin/attendance';
                        window.location.href = baseUrl + '/' + sessionNumber;
                    }
                }
            });

            // On initial page load
            window.addEventListener('DOMContentLoaded', function() {
                // Get current session from URL or default to 1
                const pathParts = window.location.pathname.split('/');
                let currentSession = 1;
                if (pathParts.length > 3 && !isNaN(pathParts[3])) {
                    currentSession = parseInt(pathParts[3]);
                } else if (pathParts.length > 2 && !isNaN(pathParts[2])) {
                    currentSession = parseInt(pathParts[2]);
                }

                // Get stored program ID or default to first
                const storedProgramId = localStorage.getItem('selectedProgramId');
                let selectedTab = document.querySelector('.program-tab');
                if (storedProgramId) {
                    const storedTab = document.querySelector(`.program-tab[data-program-id="${storedProgramId}"]`);
                    if (storedTab) {
                        selectedTab = storedTab;
                    }
                }

                let sessionCount = 1; // Default fallback
                if (selectedTab) {
                    const durationString = selectedTab.getAttribute('data-duration');
                    if (durationString && !isNaN(durationString)) {
                        sessionCount = parseInt(durationString);
                    } else if (durationString) {
                        // Try to extract number from string like "10 weeks" or "2 months"
                        const matches = durationString.match(/(\d+)/);
                        if (matches && matches[1]) {
                            sessionCount = parseInt(matches[1]);
                        }
                    }
                    sessionCount = Math.max(1, sessionCount);
                }

                // If current session exceeds sessionCount + 1, reset to 1
                // But allow Extension session (sessionCount + 1) to be valid
                if (currentSession > sessionCount + 1) currentSession = 1;

                // Generate session tabs with current session highlighted
                generateSessionTabs(sessionCount, currentSession);

                // Set active session tab explicitly after generating session tabs
                const sessionTabs = document.getElementById('sessionTabs');
                sessionTabs.querySelectorAll('.session-tab').forEach(tab => {
                    tab.classList.remove('active-tab', 'border-blue-500', 'text-blue-600');
                    tab.classList.add('border-transparent', 'text-gray-500');
                    const tabSession = parseInt(tab.getAttribute('data-session'));
                    if (tabSession === currentSession) {
                        tab.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                        tab.classList.remove('border-transparent', 'text-gray-500');
                    }
                });

                // Show the selected program's students on page load
                if (selectedTab) selectedTab.click();

                // Initialize scroll hints
                updateScrollHints();

                // Add scroll event listener to update hints
                const container = document.querySelector('.session-tabs-container');
                if (container) {
                    container.addEventListener('scroll', updateScrollHints);
                }

                // Update scroll hints on window resize
                window.addEventListener('resize', updateScrollHints);

                // Show floating notifications if they exist
                @if(session('success'))
                    showFloatingNotification('success');
                @endif

                @if(session('error'))
                    showFloatingNotification('error');
                @endif
            });


            // Admin dropdown toggle
            document.getElementById('adminDropdown').addEventListener('click', function(e) {
                e.stopPropagation();
                document.getElementById('dropdownMenu').classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                document.getElementById('dropdownMenu').classList.add('hidden');
            });

            // Toggle sidebar collapse
            document.getElementById('toggleSidebar').addEventListener('click', function() {
                const sidebar = document.querySelector('.sidebar');
                const contentArea = document.querySelector('.content-area');
                sidebar.classList.toggle('sidebar-collapsed');
                contentArea.classList.toggle('ml-1');

                // Save collapsed state
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    localStorage.setItem('sidebar-collapsed', 'true');
                } else {
                    localStorage.setItem('sidebar-collapsed', 'false');
                }

                // Rotate the icon
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-chevron-left');
                icon.classList.toggle('fa-chevron-right');
            });

            // Mobile menu toggle
            document.getElementById('mobileMenuButton').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('hidden');
            });

            // Set active nav item
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    navItems.forEach(nav => nav.classList.remove('active-nav'));
                    this.classList.add('active-nav');
                    const url = this.getAttribute('data-url');
                    if (url) {
                        window.location.href = url;
                    }
                });
            });

            // Responsive adjustments
            function handleResize() {
                if (window.innerWidth < 768) {
                    document.querySelector('.sidebar').classList.add('sidebar-collapsed');
                    document.querySelector('.content-area').classList.add('ml-1');
                } else {
                    document.querySelector('.sidebar').classList.remove('sidebar-collapsed');
                    document.querySelector('.content-area').classList.add('ml-1');
                }
            }

            window.addEventListener('resize', handleResize);
            handleResize(); // Run once on load

            // Logout functionality
            document.getElementById('logoutButton').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('logout-form').submit();
            });

            window.addEventListener('DOMContentLoaded', () => {
                const sidebar = document.querySelector('.sidebar');
                const contentArea = document.querySelector('.content-area');
                const toggleIcon = document.querySelector('#toggleSidebar i');

                if (localStorage.getItem('sidebar-collapsed') === 'true') {
                    sidebar.classList.add('sidebar-collapsed');
                    contentArea.classList.add('ml-1');
                    if (toggleIcon.classList.contains('fa-chevron-left')) {
                        toggleIcon.classList.remove('fa-chevron-left');
                        toggleIcon.classList.add('fa-chevron-right');
                    }
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    contentArea.classList.remove('ml-1');
                    if (toggleIcon.classList.contains('fa-chevron-right')) {
                        toggleIcon.classList.remove('fa-chevron-right');
                        toggleIcon.classList.add('fa-chevron-left');
                    }
                }

                // Add checkbox change event listeners
                document.querySelectorAll('input[name="attendance[]"]').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const dateDisplay = document.getElementById('dateDisplay');
                        const currentDateSpan = document.getElementById('currentDate');
                        const saveBtn = document.getElementById('saveAttendanceBtn');

                        const anyChecked = Array.from(document.querySelectorAll('input[name="attendance[]"]')).some(cb => cb.checked);

                        if (anyChecked) {
                            // Show today's date and save button
                            displayDate();
                            saveBtn.classList.remove('hidden');
                        } else {
                            // Hide date display and save button if no checkboxes are checked
                            dateDisplay.classList.add('hidden');
                            saveBtn.classList.add('hidden');
                        }
                    });
                });

                // Handle attendance form submission
                document.getElementById('attendanceForm').addEventListener('submit', function(e) {
                    // Let the form submit normally - the checkboxes will be sent automatically
                    // No need for custom JavaScript handling
                });
            });

        </script>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
        @stack('scripts')

        <script>
            // Show modal if there is a completed student count in session success message
            document.addEventListener('DOMContentLoaded', function() {
                const successMessage = document.getElementById('floatingSuccessMessage');
                if (successMessage) {
                    const messageText = successMessage.textContent || '';
                    const completedMatch = messageText.match(/(\d+)\sstudent\/s Completed All the Sessions in the Program (.+)\./);
                    if (completedMatch) {
                        const count = completedMatch[1];
                        const programName = completedMatch[2];
                        const modal = document.getElementById('completedSessionsModal');
                        const messageElem = document.getElementById('completedSessionsMessage');
                        const viewAllBtn = document.getElementById('viewAllCertificatesBtn');
                        const closeBtn = document.getElementById('closeModalBtn');

                        messageElem.textContent = `${count} student/s Completed All the Sessions in the Program ${programName}.`;

                        modal.classList.remove('hidden');

                        viewAllBtn.addEventListener('click', function() {
                            window.location.href = "{{ route('admin.certificates') }}";
                        });

                        closeBtn.addEventListener('click', function() {
                            modal.classList.add('hidden');
                        });
                    }
                }
            });
        </script>

    <!-- Include Admin Profile Modal -->
    @include('Admin.Top.profile')

    </body>
    </html>
