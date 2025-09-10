<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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

        /* Modal Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-slide-up {
            animation: slideUp 0.4s ease-out;
        }

        /* Button hover effects */
        .btn-hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Gradient text effects */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
                    <img src="<?php echo e(asset('pictures/logo.png')); ?>" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <span class="logo-text ml-3 font-bold text-xl">Student Portal</span>
                <div class="ml-auto">
                    <i class="fas fa-chevron-left text-blue-300"></i>
                </div>
            </button>

            <!-- User Profile -->
            <div class="user-profile p-4 flex items-center border-b border-blue-800">
                <?php if($enrollment && $enrollment->photo): ?>
                    <img src="<?php echo e(asset('storage/' . $enrollment->photo)); ?>" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                <?php elseif($student && $student->photo): ?>
                    <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                <?php else: ?>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center shadow">
                        <i class="fas fa-user text-white"></i>
                    </div>
                <?php endif; ?>
                <div class="ml-3 user-details">
                    <div class="font-medium text-white"><?php echo e($student->name); ?></div>
                    <div class="text-xs text-blue-200"><?php echo e($enrollment->program->name ?? 'No Program Enrolled'); ?></div>
                </div>
            </div>

            <!-- Navigation -->
            <?php echo $__env->make('Student.partials.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
                        Dashboard
                    </h2>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="relative group">
                        <button id="adminDropdown" class="flex items-center focus:outline-none space-x-2">
                            <?php if($enrollment && $enrollment->photo): ?>
                                <img src="<?php echo e(asset('storage/' . $enrollment->photo)); ?>" alt="Profile" class="h-9 w-9 rounded-full object-cover">
                            <?php elseif($student && $student->photo): ?>
                                <img src="<?php echo e(asset('storage/' . $student->photo)); ?>" alt="Profile" class="h-9 w-9 rounded-full object-cover">
                            <?php else: ?>
                                <div class="h-9 w-9 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            <?php endif; ?>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-700"><?php echo e(ucfirst(explode(' ', auth()->user()->name)[0])); ?></p>
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
            
            <!-- Dashboard Content -->
            <div class="p-6">
                <!-- Alerts -->
                <?php if(session('success')): ?>
                    <div class="mb-6">
                        <div class="alert alert-success rounded-lg p-4 border-l-4 border-green-500 bg-green-50">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        <?php echo e(session('success')); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Enrollment Status Message -->
                <?php if($enrollmentStatusMessage): ?>
                    <div class="mb-6">
                        <div class="alert alert-<?php echo e($enrollmentStatusMessage['type']); ?> rounded-lg p-4 border-l-4 border-<?php echo e($enrollmentStatusMessage['type']); ?>-500 bg-<?php echo e($enrollmentStatusMessage['type']); ?>-50">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <?php if($enrollmentStatusMessage['type'] === 'info'): ?>
                                        <svg class="h-5 w-5 text-<?php echo e($enrollmentStatusMessage['type']); ?>-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    <?php elseif($enrollmentStatusMessage['type'] === 'warning'): ?>
                                        <svg class="h-5 w-5 text-<?php echo e($enrollmentStatusMessage['type']); ?>-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    <?php elseif($enrollmentStatusMessage['type'] === 'success'): ?>
                                        <svg class="h-5 w-5 text-<?php echo e($enrollmentStatusMessage['type']); ?>-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    <?php elseif($enrollmentStatusMessage['type'] === 'error'): ?>
                                        <svg class="h-5 w-5 text-<?php echo e($enrollmentStatusMessage['type']); ?>-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-<?php echo e($enrollmentStatusMessage['type']); ?>-800">
                                        <?php echo e($enrollmentStatusMessage['message']); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Registration Fee Payment Modal - COMPLETE ACCESS BLOCK -->
                <?php
                    $registrationFeePaid = app('App\Http\Controllers\StudentController')->checkRegistrationFeeStatus(auth()->user());
                    $registrationFeeAmount = $enrollment && $enrollment->program ? $enrollment->program->registration_fee : 0;
                    $enrollmentApproved = $enrollment && $enrollment->status === \App\Models\Enrollment::STATUS_APPROVED;
                    $showBlockingModal = !$registrationFeePaid && $enrollmentApproved;
                ?>

                <?php if(!$registrationFeePaid && $registrationFeeAmount > 0 && $enrollmentApproved): ?>
                    <!-- Registration Fee Payment Modal -->
                    <div id="registrationFeeModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50 animate-fade-in">
                        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 transform transition-all duration-300 scale-100 animate-slide-up">
                            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-6 rounded-t-2xl relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-red-400 to-red-500 opacity-50"></div>
                                <div class="relative flex items-center">
                                    <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold">Registration Fee Required</h3>
                                </div>
                            </div>
                            <div class="p-8">
                                <div class="text-center mb-8">
                                    <div class="bg-gradient-to-br from-red-100 to-red-200 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-lg">
                                        <i class="fas fa-lock text-red-600 text-3xl"></i>
                                    </div>
                                    <h4 class="text-2xl font-bold text-gray-800 mb-3">Access Blocked</h4>
                                    <p class="text-gray-600 mb-6 leading-relaxed">
                                        You must pay the registration fee of <span class="font-bold text-red-600 text-lg">₱<?php echo e(number_format($registrationFeeAmount, 2)); ?></span>
                                        before you can access any features of the student portal.
                                    </p>
                                    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-5 mb-6 shadow-sm">
                                        <div class="flex items-start">
                                            <div class="bg-yellow-200 p-2 rounded-full mr-3">
                                                <i class="fas fa-info-circle text-yellow-600"></i>
                                            </div>
                                            <p class="text-sm text-yellow-800 font-medium">
                                                This is a mandatory requirement and cannot be bypassed.
                                                Please complete the payment to continue.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <button id="payButton" onclick="initiatePayMongoCheckout()" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                        <i class="fas fa-credit-card mr-3 text-lg"></i>
                                        <span class="text-lg">Pay Registration Fee (₱<?php echo e(number_format($registrationFeeAmount, 2)); ?>)</span>
                                    </button>
                                </div>
                                <div class="mt-6 text-center">
                                    <p class="text-sm text-gray-500 leading-relaxed">
                                        After payment, you will have full access to attendance records, payment history, certificates, and all other features.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Onsite Payment Message Modal -->
                    <div id="onsitePaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                            <div class="bg-blue-600 text-white p-4 rounded-t-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-2xl mr-3"></i>
                                    <h3 class="text-lg font-bold">Payment Verification Pending</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="text-center mb-6">
                                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-hourglass-half text-blue-600 text-2xl"></i>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Waiting for Admin Verification</h4>
                                    <p class="text-gray-600 mb-4">
                                        Your onsite payment will be verified by the administrator. Please wait until your status is updated to "Enrolled".
                                    </p>
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-blue-800">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            You cannot access any features until the admin verifies your payment and updates your enrollment status.
                                        </p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <button onclick="closeOnsiteModal()" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-xl p-6 text-white mb-6 shadow-lg">
                    <h2 class="text-2xl font-bold mb-2">Welcome back, <?php echo e(ucfirst(explode(' ', auth()->user()->name)[0])); ?>!</h2>
                    <p class="text-blue-100">Here's what's happening with your account today.</p>
                </div>

                <!-- Enhanced Program Card -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1"><?php echo e($enrollment->program->name ?? 'No Program Enrolled'); ?></h3>
                            <p class="text-indigo-100"><?php echo e($enrollment->program->description ?? 'No Program Description Available'); ?></p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-full">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                            <p class="text-indigo-100">Start Date</p>
                            <p class="font-medium">
                                <?php if(isset($startDate)): ?>
                                    <?php echo e(\Carbon\Carbon::parse($startDate)->format('M d, Y')); ?>

                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-indigo-100">Expected Graduation</p>
                            <p class="font-medium">
                                <?php if(isset($expectedGraduation)): ?>
                                    <?php echo e(\Carbon\Carbon::parse($expectedGraduation)->format('M d, Y')); ?>

                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-indigo-100">Number of Sessions</p>
                            <p class="font-medium">
                                <?php if(isset($numberOfSessions)): ?>
                                    <?php echo e($numberOfSessions); ?>

                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span>Progress</span>
                                <span><?php echo e($progressPercentage ?? 0); ?>% Complete</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full" style="width: <?php echo e($progressPercentage ?? 0); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Enhanced Attendance Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-blue-100/50 text-blue-600 mr-4">
                                    <i class="fas fa-user-graduate text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Session Attendance</p>
                                    <h3 class="text-2xl font-bold text-gray-800">
                                        <?php echo e($attendance['attended_sessions'] ?? '--'); ?>/<?php echo e($attendance['total_sessions'] ?? '--'); ?>

                                    </h3>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-blue-600"><?php echo e(isset($attendance['attendance_percentage']) ? $attendance['attendance_percentage'] . '%' : '--'); ?></span>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Attended: <?php echo e($attendance['attended_sessions'] ?? '--'); ?> sessions</span>
                            <span>Remaining: <?php echo e(isset($attendance['total_sessions'], $attendance['attended_sessions']) ? $attendance['total_sessions'] - $attendance['attended_sessions'] : '--'); ?> sessions</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full" style="width: <?php echo e($attendance['attendance_percentage'] ?? 0); ?>%"></div>
                            </div>
                            <div class="mt-2 flex justify-between text-xs">
                            <span class="text-gray-500">Last attended: <?php echo e($attendance['last_attended'] ?? '--'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Payments Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                    <i class="fas fa-money-bill-wave text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm">Payment Status</p>
                                    <h3 class="text-xl font-bold">$<?php echo e(number_format($payments['total_paid'] ?? 0)); ?> Paid</h3>
                                </div>
                            </div>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full capitalize"><?php echo e($payments['payment_status'] ?? 'current'); ?></span>
                        </div>
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Latest Payment Date:</span>
                                <span class="font-medium"><?php echo e($payments['payment_date'] ?? '--'); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Amount Paid:</span>
                                <span class="font-medium">$<?php echo e(number_format($payments['total_paid'] ?? 0)); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-medium"><?php echo e($payments['payment_method'] ?? '--'); ?></span>
                            </div>
                        </div>
                            <button onclick="window.location.href='<?php echo e(route('student.payment')); ?>'" class="mt-3 w-full text-sm bg-green-50 hover:bg-green-100 text-green-600 py-1 px-3 rounded-lg transition duration-200 cursor-pointer">
                                Make Payment Via Gcash
                            </button>
                    </div>

                    <!-- Certificates Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                    <i class="fas fa-certificate text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm">Certificates</p>
                                    <h3 class="text-xl font-bold"><?php echo e($certificates['total_certificates'] ?? 0); ?> Earned</h3>
                                </div>
                            </div>
                            <?php if($certificates['is_eligible'] ?? false): ?>
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Eligible</span>
                            <?php else: ?>
                                <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded-full">Not Eligible</span>
                            <?php endif; ?>
                        </div>
                        <div class="mt-4">
                            <?php if($certificates['is_eligible'] ?? false): ?>
                                <p class="text-sm text-green-600 font-medium">You are eligible for a certificate!</p>
                                <p class="text-xs text-gray-500 mt-1">Attendance: <?php echo e($certificates['attendance_percentage'] ?? 0); ?>%</p>
                            <?php else: ?>
                                <p class="text-sm text-gray-600">
                                    <?php if(($certificates['attendance_percentage'] ?? 0) > 0): ?>
                                        <?php echo e(80 - ($certificates['attendance_percentage'] ?? 0)); ?>% more attendance needed
                                    <?php else: ?>
                                        Not yet eligible
                                    <?php endif; ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Attendance: <?php echo e($certificates['attendance_percentage'] ?? 0); ?>% / 100% required</p>
                            <?php endif; ?>
                            <button onclick="window.location.href='<?php echo e(route('student.certificate')); ?>'" class="mt-2 text-sm text-purple-600 hover:text-purple-800 font-medium cursor-pointer">View Certificates</button>
                        </div>
                    </div>

                    <!-- Enhanced Sessions Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                                    <i class="fas fa-calendar-alt text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm">Upcoming Sessions</p>
                                    <h3 class="text-xl font-bold"><?php echo e($thisWeekSessionsCount); ?> This Week</h3>
                                </div>
                            </div>
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Active</span>
                        </div>
                        <div class="mt-4 space-y-2">
                            <?php if(count($upcomingSessions) > 0): ?>
                                <?php $__currentLoopData = $upcomingSessions->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center text-sm">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                        <span class="font-medium">
                                            <?php echo e(\Carbon\Carbon::parse($session->session_date)->format('D:')); ?>

                                        </span>
                                        <span class="ml-1 text-gray-600">
                                            <?php echo e($session->session_name ?? 'Session'); ?> 
                                            (<?php echo e(\Carbon\Carbon::parse($session->start_time)->format('g:i A')); ?> - 
                                            <?php echo e(\Carbon\Carbon::parse($session->end_time)->format('g:i A')); ?>)
                                        </span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if(count($upcomingSessions) > 2): ?>
                                    <div class="text-xs text-gray-500 mt-2">
                                        +<?php echo e(count($upcomingSessions) - 2); ?> more sessions
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-sm text-gray-500">
                                    No upcoming sessions scheduled
                                </div>
                            <?php endif; ?>
                        </div>
                        <button class="mt-3 w-full text-sm bg-yellow-50 hover:bg-yellow-100 text-yellow-600 py-1 px-3 rounded-lg transition duration-200">
                            View Full Schedule
                        </button>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Recent Activity</h3>
                        <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</button>
                    </div>
                    <div class="space-y-4">
                        <?php if(count($recentActivities) > 0): ?>
                            <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-start">
                                    <?php if($activity['type'] === 'attendance'): ?>
                                        <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Attendance marked</p>
                                            <p class="text-sm text-gray-500"><?php echo e($activity['description']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo e($activity['date']); ?></p>
                                        </div>
                                    <?php elseif($activity['type'] === 'payment'): ?>
                                        <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Payment received</p>
                                            <p class="text-sm text-gray-500"><?php echo e($activity['description']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo e($activity['date']); ?></p>
                                        </div>
                                    <?php elseif($activity['type'] === 'certificate'): ?>
                                        <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                                            <i class="fas fa-certificate"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Certificate earned</p>
                                            <p class="text-sm text-gray-500"><?php echo e($activity['description']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo e($activity['date']); ?></p>
                                        </div>
                                    <?php elseif($activity['type'] === 'session'): ?>
                                        <div class="p-2 rounded-full bg-orange-100 text-orange-600 mr-3">
                                            <i class="fas fa-calendar-plus"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Session scheduled</p>
                                            <p class="text-sm text-gray-500"><?php echo e($activity['description']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo e($activity['date']); ?></p>
                                        </div>
                                    <?php elseif($activity['type'] === 'info'): ?>
                                        <div class="p-2 rounded-full bg-gray-100 text-gray-600 mr-3">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Information</p>
                                            <p class="text-sm text-gray-500"><?php echo e($activity['description']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo e($activity['date']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-gray-500">No recent activities found</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Session Attendance</h3>
                        <p class="text-gray-600 mb-4">View and manage your class attendance records.</p>
                        <button onclick="window.location.href='<?php echo e(route('student.attendance')); ?>'" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-200 cursor-pointer">
                            View Attendance
                        </button>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Payment Records</h3>
                        <p class="text-gray-600 mb-4">Check your payment history and upcoming dues.</p>
                        <button onclick="window.location.href='<?php echo e(route('student.payment')); ?>'" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-200 cursor-pointer">
                            View Payments
                        </button>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Certificates</h3>
                        <p class="text-gray-600 mb-4">Download your earned certificates and view progress.</p>
                        <button onclick="window.location.href='<?php echo e(route('student.certificate')); ?>'" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg transition duration-200 cursor-pointer">
                            View Certificates
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
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
        function initiatePayMongoCheckout() {
            // Use AJAX to call the processPayment endpoint for registration fee
            const amount = <?php echo e($registrationFeeAmount); ?>;
            const email = '<?php echo e(auth()->user()->email); ?>';
            const sessionCount = 1; // Registration fee is for 1 session

            fetch('<?php echo e(route("payment.direct")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    amount: amount,
                    email: email,
                    session_count: sessionCount,
                    payment_type: 'registration'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide the registration fee modal
                    const modal = document.getElementById('registrationFeeModal');
                    if (modal) {
                        modal.style.display = 'none';
                    }
                    // Reload the page or update UI to reflect enrollment status
                    location.reload();
                } else {
                    alert('Payment failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error processing payment:', error);
                alert('An error occurred while processing payment. Please try again.');
            });
        }

        function showOnsitePaymentMessage() {
            // Show the onsite payment modal
            document.getElementById('onsitePaymentModal').classList.remove('hidden');
        }

        function closeOnsiteModal() {
            // Hide the onsite payment modal
            document.getElementById('onsitePaymentModal').classList.add('hidden');
        }

        function contactAdmin() {
            // Open email client or redirect to contact page
            const subject = encodeURIComponent('Registration Fee Payment Inquiry');
            const body = encodeURIComponent('Hello,\n\nI would like to inquire about the registration fee payment process.\n\nThank you.');
            window.location.href = 'mailto:admin@school.com?subject=' + subject + '&body=' + body;
        }
    </script>
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>
    
    <?php echo $__env->make('Student.Top.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Student/dashboard.blade.php ENDPATH**/ ?>