<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bohol Northern Star College - Modular Class Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
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
                        <img src="{{ asset('pictures/logo.png') }}" alt="BNSC Logo" class="h-10 w-10 mr-2">
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
                    <a href="{{ route('welcome')}}" class="nav-link text-white hover:text-blue-300 px-3 py-2 text-sm font-medium">Home</a>
                    <a href="{{ route('about') }}" class="nav-link text-white hover:text-blue-300 px-3 py-2 text-sm font-medium">About</a>
                    <a href="{{ route('programs') }}" class="nav-link text-white hover:text-blue-300 px-3 py-2 text-sm font-medium">Programs</a>
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
                <a href="{{ route('welcome')}}" class="text-white hover:text-blue-300 block px
                <a href=" {{ route('about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-blue-300 hover:bg-blue-800">About</a>
                <a href="{{ route('programs') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-blue-300 hover:bg-blue-800">Programs</a>
                <div class="pt-2">
                    <button class="w-full flex justify-center items-center text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium" onclick="document.getElementById('login-modal').classList.remove('hidden')">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-b from-blue-900 to-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to BNSC Modular Class Portal</h1>
                <p class="text-xl md:text-2xl mb-8">Empowering Skills, One Session at a Time — Your Gateway to Flexible, Hands-On Learning.</p>
                <div class="flex justify-center">
                    <a href="{{ route('enrollment') }}" class="bg-white text-blue-700 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold text-lg shadow-lg transition duration-300 transform hover:scale-105 flex items-center">
                        Enroll Now <i class="fas fa-user-plus ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- School Pictures Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Our Campus</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ asset('pictures/bnc_welc.jpg') }}" alt="School Building" class="w-full h-64 object-cover">
                </div>
                <div class="rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ asset('pictures/comlab.jpg') }}" alt="Classroom" class="w-full h-64 object-cover">
                </div>
                <div class="rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ asset('pictures/hallway.jpg') }}" alt="Library" class="w-full h-64 object-cover">
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Our School Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Why Choose Our School</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-award text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Quality Education</h3>
                    <p class="text-gray-600">Our institution is recognized for academic excellence and producing competent graduates.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-chalkboard-teacher text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Expert Faculty</h3>
                    <p class="text-gray-600">Learn from experienced professors and industry professionals dedicated to student success.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-network-wired text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Industry Connections</h3>
                    <p class="text-gray-600">Strong partnerships with leading companies for internships and job placements.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" id="main-content">
        <!-- Features Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Key Features of Our Portal</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-user-graduate text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Modular Enrollment & Class Scheduling</h3>
                    <p class="text-gray-600">Easily enroll students in modular programs with automatic tracking of their class schedules, assignments, and progress. Our system supports both manual and online enrollment with admin oversight.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-file-upload text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Module Upload & Assignment Submission</h3>
                    <p class="text-gray-600">Instructors can upload digital learning modules, and students can submit their completed modules directly through the portal. Submissions are tracked by deadlines and monitored for completion.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-chart-pie text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Real-time Progress & Payment Monitoring</h3>
                    <p class="text-gray-600">Admins and cashiers can monitor student payment status, while instructors and students can view academic progress in real-time. Role-based dashboards provide personalized views for effective management.</p>
                </div>

            </div>
        </div>
    </div>
    </main>

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
                <p>&copy; 2025 Bohol Northern Star College. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @include('partials.login_modal')
    <script>
        // Add loading spinner to login form
        document.querySelector('form[action="{{ route('custom.login') }}"]').addEventListener('submit', function(e) {
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

    </script>

    @if ($errors->has('username') || $errors->has('password'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('login-modal').classList.remove('hidden');
        });

    </script>
    @endif
</body>
</html>
