<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
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
        }

    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-900 text-white w-56 flex flex-col">
            <!-- Logo (Collapse Button) -->
            <button id="toggleSidebar" class="p-3 flex items-center justify-center border-b border-blue-800 w-full hover:bg-blue-800/80 transition-colors">
                <div class="bg-blue-800 p-1 rounded-lg">
                    <img src="{{ asset('pictures/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <span class="logo-text ml-3 font-bold text-xl">Student Portal</span>
                <div class="ml-auto">
                    <i class="fas fa-chevron-left text-blue-300"></i>
                </div>
            </button>

            <!-- User Profile -->
            <div class="user-profile p-4 flex items-center border-b border-blue-800">
                @if($enrollment && $enrollment->photo)
                    <img src="{{ asset('storage/' . $enrollment->photo) }}" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                @elseif($student && $student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                @else
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center shadow">
                        <i class="fas fa-user text-white"></i>
                    </div>
                @endif
                <div class="ml-3 user-details">
                    <div class="font-medium text-white">{{ $student->name }}</div>
                    <div class="text-xs text-blue-200">{{ $enrollment->program->name ?? 'No Program Enrolled' }}</div>
                </div>
            </div>

            <!-- Navigation -->
            @include('Student.partials.navigation')
            
        </div>
        
        <!-- Main Content -->
        <div class="content-area flex-1 overflow-y-auto">
            <!-- Enhanced Top Bar -->
            <div class="bg-white shadow-md p-4 flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center space-x-4">
                    <button id="mobileMenuButton" class="text-gray-500 hover:text-gray-700 md:hidden transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-gray-800 bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">
                        Certificates
                    </h2>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="flex space-x-5">
                        <div class="relative group">
                            <button class="text-gray-500 hover:text-gray-700 transition-colors relative">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center">3</span>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
                                <div class="p-3 border-b border-gray-200">
                                    <h4 class="font-medium text-gray-800">Notifications (3)</h4>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-calendar-check text-blue-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">New session scheduled</p>
                                                <p class="text-xs text-gray-500 mt-1">Web Development - Tomorrow 10AM</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-money-bill-wave text-green-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">Payment received</p>
                                                <p class="text-xs text-gray-500 mt-1">$500 for 2 Sessions</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-purple-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-certificate text-purple-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">Certificate available</p>
                                                <p class="text-xs text-gray-500 mt-1">JavaScript Fundamentals</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-3 text-center border-t border-gray-200">
                                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <button class="text-gray-500 hover:text-gray-700 transition-colors relative">
                                <i class="fas fa-envelope text-xl"></i>
                                <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-blue-500 text-white text-xs flex items-center justify-center">2</span>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
                                <div class="p-3 border-b border-gray-200">
                                    <h4 class="font-medium text-gray-800">Messages (2)</h4>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-user-graduate text-blue-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">From: Advisor</p>
                                                <p class="text-xs text-gray-500 mt-1">About your course selection...</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-yellow-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">System Notification</p>
                                                <p class="text-xs text-gray-500 mt-1">Upcoming maintenance...</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-3 text-center border-t border-gray-200">
                                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All Messages</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-l h-8 border-gray-200"></div>

                    <div class="relative group">
                        <button id="adminDropdown" class="flex items-center focus:outline-none space-x-2">
                            @if($enrollment && $enrollment->photo)
                                <img src="{{ asset('storage/' . $enrollment->photo) }}" alt="Profile" class="h-9 w-9 rounded-full object-cover">
                            @elseif($student && $student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="Profile" class="h-9 w-9 rounded-full object-cover">
                            @else
                                <div class="h-9 w-9 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-700">{{ ucfirst(explode(' ', auth()->user()->name)[0]) }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="#" onclick="openProfileModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                            <a href="#" id="logoutButton" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Certificate Content -->
            <div class="p-6">
                <!-- Registration Fee Payment Modal -->
                @php
                    $registrationFeePaid = app('App\Http\Controllers\StudentController')->checkRegistrationFeeStatus(auth()->user());
                    $registrationFeeAmount = $enrollment && $enrollment->program ? $enrollment->program->registration_fee : 0;
                    $enrollmentApproved = $enrollment && $enrollment->status === \App\Models\Enrollment::STATUS_APPROVED;
                @endphp
                @if(!$registrationFeePaid && $registrationFeeAmount > 0 && $enrollmentApproved)
                    <div id="registrationFeeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                            <div class="bg-red-600 text-white p-4 rounded-t-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
                                    <h3 class="text-lg font-bold">Registration Fee Required</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="text-center mb-6">
                                    <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-lock text-red-600 text-2xl"></i>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Access Blocked</h4>
                                    <p class="text-gray-600 mb-4">
                                        You must pay the registration fee of <span class="font-bold text-red-600">₱{{ number_format($registrationFeeAmount, 2) }}</span>
                                        before you can access any features of the student portal.
                                    </p>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-yellow-800">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            This is a mandatory requirement and cannot be bypassed.
                                            Please complete the payment to continue.
                                        </p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <button onclick="payRegistrationFee()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        Pay Registration Fee (₱{{ number_format($registrationFeeAmount, 2) }})
                                    </button>
                                    <button onclick="contactAdmin()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-envelope mr-2"></i>
                                        Contact Administrator
                                    </button>
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="text-xs text-gray-500">
                                        After payment, you will have full access to attendance records, payment history, certificates, and all other features.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($enrollment && $enrollment->isEligibleForCertificate())
                    <!-- Show Certificate if enrollment is completed -->
                    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="p-8">
                            <div class="text-center mb-8">
                                <img src="{{ asset('pictures/logo.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
                                <h1 class="text-3xl font-bold text-gray-800">Certificate of Completion</h1>
                                <p class="text-blue-600 mt-2">This certifies that</p>
                            </div>
                            
                            <div class="text-center mb-10">
                                <h2 class="text-4xl font-bold text-gray-800 mb-2">{{ $student->name }}</h2>
                                <p class="text-gray-600">has successfully completed the program</p>
                                <p class="text-xl font-semibold text-blue-600 mt-2">{{ $enrollment->program->name ?? 'No program enrolled' }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center mt-12">
                                <div class="text-center">
                                    <div class="border-t-2 border-gray-300 w-32 mx-auto mb-2"></div>
                                    <p class="text-sm text-gray-600">Date Completed</p>
                                    <p class="font-medium">{{ $enrollment->completion_date ? $enrollment->completion_date->format('F j, Y') : date('F j, Y') }}</p>
                                </div>
                                <div class="text-center">
                                    <div class="border-t-2 border-gray-300 w-32 mx-auto mb-2"></div>
                                    <p class="text-sm text-gray-600">Instructor</p>
                                    <p class="font-medium">John Smith</p>
                                </div>
                            </div>
                            
                            <div class="mt-12 text-center">
                                <p class="text-sm text-gray-600">Director of Education</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 text-center">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                            <i class="fas fa-download mr-2"></i> Download Certificate
                        </button>
                    </div>
                @else
                    <!-- Show Certificate Not Available if enrollment is not completed -->
                    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md overflow-hidden p-8 text-center">
                        <div class="text-blue-500 mb-4">
                            <i class="fas fa-certificate text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Certificate Not Available</h3>
                        <p class="text-gray-600">You haven't completed the program yet. Complete all requirements to receive your certificate.</p>
                        <div class="mt-6">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Progress</span>
                                <span>{{ $certificates['attendance_percentage'] ?? 0 }}% Complete</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full" style="width: {{ $certificates['attendance_percentage'] ?? 0 }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Attendance: {{ $certificates['attendance_percentage'] ?? 0 }}% / 100% required</p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // student dropdown toggle
        document.getElementById('adminDropdown').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.add('hidden');
        });
        // Toggle sidebar collapse
        document.getElementById('toggleSidebar').addEventListener('click', function () {
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
                // Remove active class from all items
                navItems.forEach(nav => nav.classList.remove('active-nav'));
                // Add active class to clicked item
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
                document.querySelector('.content-area').classList.remove('ml-1');
                document.querySelector('.content-area').classList.add('ml-1');
            } else {
                document.querySelector('.sidebar').classList.remove('sidebar-collapsed');
                document.querySelector('.content-area').classList.remove('ml-1');
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
                contentArea.classList.remove('ml-1');
                contentArea.classList.add('ml-1');

                // Set the correct icon direction
                if (toggleIcon.classList.contains('fa-chevron-left')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            }

            // Show registration fee modal if it exists
            const modal = document.getElementById('registrationFeeModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        });

        // Registration fee payment functions
        function payRegistrationFee() {
            // Redirect to payment page with registration fee flag
            window.location.href = '{{ route("student.payment") }}?type=registration_fee';
        }

        function contactAdmin() {
            // Open email client or redirect to contact page
            const subject = encodeURIComponent('Registration Fee Payment Inquiry');
            const body = encodeURIComponent('Hello,\n\nI would like to inquire about the registration fee payment process.\n\nThank you.');
            window.location.href = 'mailto:admin@school.com?subject=' + subject + '&body=' + body;
        }
    </script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
    
    @include('Student.Top.profile')
</body>
</html>
