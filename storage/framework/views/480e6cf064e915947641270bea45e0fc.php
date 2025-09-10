<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Enrollment Form</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsQR/1.4.0/jsQR.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles */
        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .logo-container {
            transition: all 0.3s ease;
        }

        .logo-container:hover {
            transform: scale(1.05);
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #3b82f6;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .login-btn {
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-8px);
            }

            40% {
                transform: translateX(8px);
            }

            60% {
                transform: translateX(-8px);
            }

            80% {
                transform: translateX(8px);
            }

            100% {
                transform: translateX(0);
            }
        }

        .shake {
            animation: shake 0.5s ease;
        }

    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navigation Bar -->
    <nav class="bg-gradient-to-b from-blue-900 to-blue-600 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo and School Name -->
                <div class="flex-shrink-0 flex items-center logo-container">
                    <div class="flex items-center">
                        <img src="<?php echo e(asset('pictures/logo.png')); ?>" alt="BNSC Logo" class="h-10 w-10 mr-2">
                        <span class="text-xl font-bold text-white">
                            Bohol Northern Star College
                        </span>
                    </div>
                    <span class="ml-2 text-sm font-medium text-blue-200 hidden md:inline">
                        - Modular Class Portal
                    </span>
                </div>

                <!-- Center Navigation Links -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="<?php echo e(route('welcome')); ?>" class="nav-link text-white hover:text-blue-300 px-3 py-2 text-sm font-medium">Home</a>
                    <a href="<?php echo e(route('about')); ?>" class="nav-link text-white hover:text-blue-300 px-3 py-2 text-sm font-medium">About</a>
                    <a href="<?php echo e(route('programs')); ?>" class="nav-link text-white hover:text-blue-300 px-3 py-2 text-sm font-medium">Programs</a>
                </div>

                <!-- Login Button -->
                <div class="relative">
                    <button class="login-btn flex items-center text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition-all duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </button>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 focus:outline-none" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gradient-to-b from-blue-900 to-blue-600">
                <a href="<?php echo e(route('welcome')); ?>" class="text-white hover:text-blue-300 block px<-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href=" <?php echo e(route('about')); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-blue-300 hover:bg-blue-800">About</a>
                <a href="<?php echo e(route('programs')); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-blue-300 hover:bg-blue-800">Programs</a>
                <div class="pt-2">
                    <button class="w-full flex justify-center items-center text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium" onclick="document.getElementById('login-modal').classList.remove('hidden')">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </button>
                </div>
            </div>
        </div>
    </nav>

        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center text-blue-900 mb-12">Enrollment Form</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Enrollment Steps -->
                <div class="bg-blue-50 p-8 rounded-lg">
                    <h3 class="text-2xl font-semibold text-blue-800 mb-6">Complete Your Enrollment in 4 Easy Steps</h3>
                    <ol class="space-y-6">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-bold mr-4">1</span>
                            <div>
                                <h4 class="text-lg font-medium text-gray-800">Fill Out the Enrollment Form</h4>
                                <p class="text-gray-600 mt-1">Provide accurate personal information including your complete name, contact details, educational background, and program preference. All fields marked with an asterisk (*) are required.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-bold mr-4">2</span>
                            <div>
                                <h4 class="text-lg font-medium text-gray-800">Upload Required Documents</h4>
                                <p class="text-gray-600 mt-1">
                                    • Upload a recent 2x2 ID picture (JPG/PNG, max 4MB)<br>
                                    • Upload parent/guardian consent form (PDF/DOC, max 4MB)<br>
                                    • Scan the QR code provided by the administration or enter the 8-digit PIN
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-bold mr-4">3</span>
                            <div>
                                <h4 class="text-lg font-medium text-gray-800">Review and Submit</h4>
                                <p class="text-gray-600 mt-1">Double-check all information for accuracy, agree to the terms and conditions, and certify that all provided information is true and correct. Click "Submit Enrollment Application" to complete your submission.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-bold mr-4">4</span>
                            <div>
                                <h4 class="text-lg font-medium text-gray-800">Wait for Approval</h4>
                                <p class="text-gray-600 mt-1">Our admissions team will review your application within <strong>48 hours</strong>. You will receive:<br>
                                • An email confirmation with your student credentials<br>
                                • Notification of approval status<br>
                                • Further instructions for payment and orientation</p>
                            </div>
                        </li>
                    </ol>
                    
                    <div class="mt-8 p-4 bg-blue-100 border-l-4 border-blue-500 rounded">
                        <h4 class="font-semibold text-blue-800 mb-2">📋 Required Documents Checklist:</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>✓ Valid ID (Student ID, Birth Certificate, or any government-issued ID)</li>
                            <li>✓ Recent 2x2 ID picture</li>
                            <li>✓ Parent/Guardian consent form (for minors)</li>
                            <li>✓ QR Code PIN from administration</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded">
                        <h4 class="font-semibold text-yellow-800 mb-2">ℹ️ Important Notes:</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• Save your login credentials provided after submission</li>
                            <li>• Check your email regularly for updates</li>
                            <li>• Contact admissions@bnsc.edu.ph for assistance</li>
                            <li>• Applications are processed Monday-Friday, 8AM-5PM</li>
                        </ul>
                    </div>
                </div>

            <!-- Enrollment Form -->
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6">Enrollment Application Form</h3>

                <!-- Success message (shown via JS) -->
                <div id="enroll-success" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <strong class="font-bold">Success!</strong>
                    <span class="block mt-1">Your enrollment has been submitted successfully.</span>
                    <span class="block mt-1">Please wait for admin approval. A confirmation will be emailed to you within 48 hours.</span>
                </div>

                <!-- Start Form -->
                <form id="enrollment-form" class="space-y-6" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first-name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="first-name" name="first_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-red-600 text-sm mt-1 error-message"></p>
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last-name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="last-name" name="last_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-red-600 text-sm mt-1 error-message"></p>
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label for="middle-name" class="block text-sm font-medium text-gray-700">Middle Name</label>
                            <input type="text" id="middle-name" name="middle_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-red-600 text-sm mt-1 error-message"></p>
                        </div>

                        <!-- Suffix Name -->
                        <div>
                            <label for="suffix-name" class="block text-sm font-medium text-gray-700">Suffix Name</label>
                            <input type="text" id="suffix-name" name="suffix_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-red-600 text-sm mt-1 error-message"></p>
                        </div>
                    </div>

                    <!-- Birthdate -->
                    <div>
                        <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" max="<?php echo e(date('Y-m-d')); ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Age -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                            <input type="number" id="age" name="age" readonly class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-red-600 text-sm mt-1 error-message"></p>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select id="gender" name="gender" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <p class="text-red-600 text-sm mt-1 error-message"></p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" id="phone" name="phone" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" placeholder="09123456789" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                       <textarea id="address" name="address" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your complete address"></textarea>
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Citizenship -->
                    <div>
                        <label for="citizenship" class="block text-sm font-medium text-gray-700">Citizenship</label>
                        <input type="text" id="citizenship" name="citizenship" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Filipino">
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Religion -->
                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700">Religion</label>
                        <input type="text" id="religion" name="religion" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Catholic">
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Place of Birth -->
                    <div>
                        <label for="place_of_birth" class="block text-sm font-medium text-gray-700">Place of Birth</label>
                        <input type="text" id="place_of_birth" name="place_of_birth" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="City/Municipality, Province">
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Civil Status -->
                    <div>
                        <label for="civil_status" class="block text-sm font-medium text-gray-700">Civil Status</label>
                        <select id="civil_status" name="civil_status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Civil Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Separated">Separated</option>
                            <option value="Divorced">Divorced</option>
                        </select>
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- If Married Name of Spouse -->
                    <div id="spouse_name_container" class="hidden">
                        <label for="spouse_name" class="block text-sm font-medium text-gray-700">Name of Spouse (if married)</label>
                        <input type="text" id="spouse_name" name="spouse_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Full name of spouse">
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Father's Name -->
                    <div>
                        <label for="father_name" class="block text-sm font-medium text-gray-700">Father's Name</label>
                        <input type="text" id="father_name" name="father_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Full name of father">
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Mother's Name -->
                    <div>
                        <label for="mother_name" class="block text-sm font-medium text-gray-700">Mother's Name</label>
                        <input type="text" id="mother_name" name="mother_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Full name of mother">
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Guardian -->
                    <div>
                        <label for="guardian" class="block text-sm font-medium text-gray-700">Guardian</label>
                        <input type="text" id="guardian" name="guardian" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Full name of guardian">
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Contact Number of Guardian -->
                    <div>
                        <label for="guardian_contact" class="block text-sm font-medium text-gray-700">Contact Number of Guardian</label>
                        <input type="tel" id="guardian_contact" name="guardian_contact" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" placeholder="09123456789" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Program -->
                    <div>
                        <label for="program" class="block text-sm font-medium text-gray-700">Program to Enroll</label>
                        <select id="program" name="program_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select a program</option>
                            <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($program->id); ?>"><?php echo e($program->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- QR Code PIN (Required for Enrollment) -->
                    <div>
                        <label for="qr_pin" class="block text-sm font-medium text-gray-700">
                            QR Code PIN <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="qr_pin" name="qr_pin" maxlength="8"
                                placeholder="Enter 8-digit PIN from QR code"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 pr-10"
                                required>
                            <div id="qr-pin-status" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none mt-1">
                                <!-- Status icons will be shown here -->
                            </div>
                        </div>
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                        <div id="qr-pin-feedback" class="text-sm mt-1"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <!-- QR Scanner Section -->
                            <div class="relative">
                                <button type="button" id="scan-qr-btn"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-300 flex items-center justify-center">
                                    <i class="fas fa-qrcode mr-2"></i>Scan QR Code
                                </button>
                                <div id="qr-scanner-container" class="hidden mt-2">
                                    <video id="qr-video" class="w-full rounded-md border border-gray-300" width="300" height="200" autoplay playsinline></video>
                                    <div id="qr-scanning-overlay" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                                        <div class="text-white text-center">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white mx-auto mb-2"></div>
                                            <p class="text-sm">Scanning...</p>
                                        </div>
                                    </div>
                                    <button type="button" id="stop-scan-btn"
                                        class="mt-2 w-full bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors duration-300">
                                        <i class="fas fa-stop mr-1"></i>Stop Scanning
                                    </button>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 mt-2">
                            Scan the QR code or enter the 8-digit PIN provided by the administration. This PIN is required for enrollment.
                        </p>
                    </div>

                    <!-- Photo Upload -->
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700">Upload Student Picture</label>
                        <input type="file" id="photo" name="photo" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-gray-500 text-sm mt-1">Accepted formats: jpg, jpeg, png. Max size: 4MB.</p>
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Parent's Consent File -->
                    <div>
                        <label for="parent_consent" class="block text-sm font-medium text-gray-700">Parent's Consent</label>
                        <input type="file" id="parent_consent" name="parent_consent" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-gray-500 text-sm mt-1">Accepted formats: jpg, jpeg, png. Max size: 4MB.</p>
                        <p class="text-red-600 text-sm mt-1 error-message"></p>
                    </div>

                    <!-- Certification Checkbox -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="certify_true" name="certify_true" type="checkbox" required class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="certify_true" class="font-medium text-gray-700">I hereby certify that all the information I provided above is true and correct to the best of my knowledge.</label>
                            <p class="text-red-600 text-sm mt-1 error-message" data-field="certify_true"></p>
                        </div>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">terms and conditions</a></label>
                            <p class="text-red-600 text-sm mt-1 error-message" data-field="terms"></p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" id="enroll-submit-btn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 relative">
                            <span id="enroll-btn-text">Submit Enrollment Application</span>
                            <span id="enroll-btn-spinner" class="hidden absolute left-1/2 -translate-x-1/2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
                <!-- Success Modal -->
                <div id="enroll-success-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full text-center">
                        <h2 class="text-2xl font-bold text-green-700 mb-3">✅ Enrollment Submitted Successfully!</h2>
                        <p class="text-gray-700 mb-2">Your account has been created and your enrollment has been received.</p>
                        <p class="text-gray-700 mb-2">You will receive a confirmation email within <strong>48 hours</strong>.</p>
                        <p class="text-blue-700 font-semibold mt-4">You may now log in to track the status of your enrollment.</p>

                        <div class="bg-blue-50 p-4 mt-4 rounded-md text-left">
                            <p><strong>Username:</strong> <span id="success-username" class="font-mono text-gray-800"></span></p>
                            <p><strong>Password:</strong> <span id="success-password" class="font-mono text-gray-800"></span></p>
                        </div>

                        <button onclick="document.getElementById('enroll-success-modal').classList.add('hidden')" class="mt-6 inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">About BNSC</h3>
                    <p class="text-gray-300">The Bohol Northern Star College, Inc., is guided by a Triad of Virtues: Educated Minds, Noble Hearts and Helpful Hands, and aims to internalize these virtues in the lives of its Faculty, Staff, and Students.</p>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <address class="text-gray-300 not-italic">
                        <p><i class="fas fa-map-marker-alt mr-2"></i> Isaac Garces St. 6315 Ubay, Bohol, Philippines</p>
                        <p><i class="fas fa-phone mr-2"></i> (038) - 500 - 0409</p>
                        <p><i class="fas fa-envelope mr-2"></i> info@bnsc.edu.ph</p>
                    </address>
                </div>

               <!-- Social Media -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Connect With Us</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/BNSCPAGE" class="text-gray-300 hover:text-white text-xl"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.youtube.com/@boholnorthernstarcollegein4253" class="text-gray-300 hover:text-white text-xl"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2023 Bohol Northern Star College. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <?php echo $__env->make('partials.login_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script>

            // Enrollment form submission with loading spinner
        document.getElementById('enrollment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('enroll-submit-btn');
            const spinner = document.getElementById('enroll-btn-spinner');
            const btnText = document.getElementById('enroll-btn-text');
            btn.disabled = true;
            btnText.textContent = 'Submitting...';
            btnText.classList.remove('opacity-0');
            btnText.classList.add('text-white');
            spinner.classList.remove('hidden');

            setTimeout(() => {
                submitEnrollmentForm(e.target, btn, spinner, btnText);
            }, 2000);
        });
        // Actual enrollment fetch logic
        function submitEnrollmentForm(form, btn, spinner, btnText) {
            let formData = new FormData(form);

            // Reset error states
            form.querySelectorAll('input, select').forEach(el => {
                el.classList.remove('border-red-500', 'shake');
            });
            form.querySelectorAll('.error-message').forEach(p => {
                p.textContent = '';
            });

            // Get the CSRF token from meta tag (more reliable method)
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                             document.querySelector('input[name="_token"]')?.value;

            // Debug: Log the form data and URL
            console.log('Submitting enrollment form to:', '/enroll');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            fetch('/enroll', {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Check if response is JSON before parsing
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // If not JSON, read as text to see what the server returned
                    return response.text().then(text => {
                        console.error('Non-JSON response received:', text);
                        throw new Error('Server returned non-JSON response: ' + text.substring(0, 100));
                    });
                }
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.errors) {
                    // Handle validation errors
                    for (let field in data.errors) {
                        let input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('border-red-500', 'shake');
                            let errorText = input.closest('div').querySelector('.error-message');
                            if (errorText) {
                                errorText.textContent = data.errors[field][0]
                                    .replace(field, field.replace('_', ' '))
                                    .replace(/The /i, '')
                                    .replace(/ is required/, ' is required.');
                            }
                        }
                    }
                } else if (data.success) {
                    // Handle success
                    document.getElementById('success-username').textContent = data.username;
                    document.getElementById('success-password').textContent = data.password;
                    document.getElementById('enroll-success-modal').classList.remove('hidden');
                    form.reset();
                }
            })
            .catch(error => {
                console.error('Submission failed:', error);
                // Show more detailed error information
                if (error.name === 'TypeError' || error.message.includes('JSON')) {
                    // This usually means the server returned a non-JSON response (like an HTML error page)
                    alert('Server error: The enrollment service is currently unavailable. Please try again later or contact support.');
                } else {
                    alert('An error occurred while submitting your enrollment: ' + error.message);
                }
            })
            .finally(() => {
                // Always reset button state
                btn.disabled = false;
                btnText.textContent = 'Submit Enrollment Application';
                spinner.classList.add('hidden');
            });
        }

        // Calculate age from birthdate
        document.getElementById('birthdate').addEventListener('change', function () {
            const birthdate = new Date(this.value);
            const today = new Date();
            
            let age = today.getFullYear() - birthdate.getFullYear();
            const m = today.getMonth() - birthdate.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }

            if (!isNaN(age)) {
                document.getElementById('age').value = age;
            } else {
                document.getElementById('age').value = '';
            }
        });

        // Show/hide spouse name field based on civil status
        document.getElementById('civil_status').addEventListener('change', function() {
            const spouseContainer = document.getElementById('spouse_name_container');
            if (this.value === 'Married') {
                spouseContainer.classList.remove('hidden');
            } else {
                spouseContainer.classList.add('hidden');
                document.getElementById('spouse_name').value = '';
            }
        });

        // Login modal functionality
        const loginBtn = document.querySelector('.login-btn');
        const loginModal = document.getElementById('login-modal');
        const closeLoginModal = document.getElementById('close-login-modal');

        loginBtn.addEventListener('click', () => {
            loginModal.classList.remove('hidden');
        });

        closeLoginModal.addEventListener('click', () => {
            loginModal.classList.add('hidden');
        });

        // Toggle password visibility
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Mobile menu toggle
        document.querySelector('[aria-controls="mobile-menu"]').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
            const mobileMenu = document.getElementById('mobile-menu');

            if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Add loading spinner to login form
        document.querySelector('form[action="<?php echo e(route('custom.login')); ?>"]').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('login-submit-btn');
            const spinner = document.getElementById('login-spinner');
            const btnText = document.getElementById('login-button-text');
            btn.disabled = true;
            btnText.textContent = 'Logging in...';
            btnText.classList.remove('opacity-0');
            btnText.classList.add('text-white');
            spinner.classList.remove('hidden');

            setTimeout(() => {
                e.target.submit();
            }, 1000);
        });
    </script>

    <script>
        // QR Code Scanner functionality
        let scanningInterval = null;
        let videoStream = null;

        document.getElementById('scan-qr-btn').addEventListener('click', function() {
            const video = document.getElementById('qr-video');
            const scannerContainer = document.getElementById('qr-scanner-container');
            const scanBtn = document.getElementById('scan-qr-btn');
            const scanningOverlay = document.getElementById('qr-scanning-overlay');
            
            // Show scanner container and hide button
            scanBtn.classList.add('hidden');
            scannerContainer.classList.remove('hidden');
            scanningOverlay.classList.remove('hidden');
            
            // Request camera access
            navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                .then(function(stream) {
                    videoStream = stream;
                    video.srcObject = stream;
                    video.setAttribute("playsinline", true);
                    video.play();
                    
                    // Start scanning
                    scanningInterval = setInterval(function() {
                        if (video.readyState === video.HAVE_ENOUGH_DATA) {
                            const canvasElement = document.createElement("canvas");
                            const canvas = canvasElement.getContext("2d");
                            canvasElement.height = video.videoHeight;
                            canvasElement.width = video.videoWidth;
                            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                            const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                            const code = jsQR(imageData.data, imageData.width, imageData.height);
                            
                            if (code) {
                                try {
                                    // Parse the QR code data (should be JSON)
                                    const qrData = JSON.parse(code.data);
                                    // Populate the qr_pin field with the unique PIN
                                    if (qrData.unique_pin) {
                                        document.getElementById('qr_pin').value = qrData.unique_pin;
                                    } else {
                                        document.getElementById('qr_pin').value = qrData.id || code.data;
                                    }
                                    
                                    // Stop scanning and show success
                                    stopScanning();
                                    
                                    // Show success message
                                    alert('QR Code scanned successfully! PIN has been populated.');
                                } catch (e) {
                                    // If not JSON, use the raw data
                                    document.getElementById('qr_pin').value = code.data;
                                    
                                    // Stop scanning and show success
                                    stopScanning();
                                    
                                    // Show success message
                                    alert('QR Code scanned successfully! PIN has been populated.');
                                }
                            }
                        }
                    }, 100); // Scan every 100ms
                })
                .catch(function(error) {
                    console.error('Error accessing camera:', error);
                    alert('Unable to access camera. Please ensure camera permissions are granted.');
                    // Reset UI state
                    scanBtn.classList.remove('hidden');
                    scannerContainer.classList.add('hidden');
                    scanningOverlay.classList.add('hidden');
                });
        });

        // Stop scanning button functionality
        document.getElementById('stop-scan-btn').addEventListener('click', function() {
            stopScanning();
        });

        function stopScanning() {
            const video = document.getElementById('qr-video');
            const scannerContainer = document.getElementById('qr-scanner-container');
            const scanBtn = document.getElementById('scan-qr-btn');
            const scanningOverlay = document.getElementById('qr-scanning-overlay');
            
            // Stop the scanning interval
            if (scanningInterval) {
                clearInterval(scanningInterval);
                scanningInterval = null;
            }
            
            // Stop the video stream
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
                video.srcObject = null;
            }
            
            // Reset UI state
            scanBtn.classList.remove('hidden');
            scannerContainer.classList.add('hidden');
            scanningOverlay.classList.add('hidden');
        }

        // Auto-stop scanning when form is submitted
        document.getElementById('enrollment-form').addEventListener('submit', function() {
            stopScanning();
        });
    </script>

    <script>
        // Real-time QR PIN validation
        document.getElementById('qr_pin').addEventListener('input', function(e) {
            const pin = e.target.value;
            const statusElement = document.getElementById('qr-pin-status');
            const feedbackElement = document.getElementById('qr-pin-feedback');
            
            // Clear previous status
            statusElement.innerHTML = '';
            feedbackElement.textContent = '';
            feedbackElement.className = 'text-sm mt-1';
            
            // Check if PIN is exactly 8 characters
            if (pin.length === 8) {
                // Show loading indicator
                statusElement.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-500"></i>';
                feedbackElement.textContent = 'Checking PIN...';
                feedbackElement.className = 'text-blue-600 text-sm mt-1';
                
                // Send AJAX request to validate PIN
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                
                fetch('/validate-qr-pin', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ qr_pin: pin })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        // Valid PIN
                        statusElement.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                        feedbackElement.textContent = '✓ Valid PIN - Ready to enroll';
                        feedbackElement.className = 'text-green-600 text-sm mt-1 font-medium';
                    } else {
                        // Invalid PIN
                        statusElement.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
                        feedbackElement.textContent = '✗ Invalid PIN - Please check and try again';
                        feedbackElement.className = 'text-red-600 text-sm mt-1 font-medium';
                        
                        // Add shake animation to input
                        e.target.classList.add('border-red-500', 'shake');
                        setTimeout(() => {
                            e.target.classList.remove('shake');
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error('Error validating PIN:', error);
                    statusElement.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500"></i>';
                    feedbackElement.textContent = '⚠️ Unable to validate PIN - Please try again';
                    feedbackElement.className = 'text-yellow-600 text-sm mt-1';
                });
            } else if (pin.length > 0 && pin.length < 8) {
                // Show character count
                feedbackElement.textContent = `${pin.length}/8 characters`;
                feedbackElement.className = 'text-gray-500 text-sm mt-1';
            }
        });

        // Clear validation when input is cleared
        document.getElementById('qr_pin').addEventListener('blur', function(e) {
            if (e.target.value.length === 0) {
                const feedbackElement = document.getElementById('qr-pin-feedback');
                feedbackElement.textContent = '';
                feedbackElement.className = 'text-sm mt-1';
            }
        });
    </script>

    <?php if($errors->has('username') || $errors->has('password')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('login-modal').classList.remove('hidden');
        });
    </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/enrollment_form.blade.php ENDPATH**/ ?>