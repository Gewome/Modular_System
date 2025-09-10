<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Programs - BNSC</title>
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

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-center text-blue-900 mb-12">Our Academic Programs</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Driving -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                <div class="h-48 bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-car text-6xl text-blue-600"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Driving with Basic Troubleshooting</h3>
                    <p class="text-gray-600 mb-4">Learn safe driving techniques and basic vehicle troubleshooting.</p>
                    <a href="#" class="text-blue-600 font-medium hover:text-blue-800 flex items-center">
                        Learn more <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Welding -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                <div class="h-48 bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-fire text-6xl text-orange-600"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Shielded Metal Arc Welding and Fabrication</h3>
                    <p class="text-gray-600 mb-4">Master welding techniques and metal fabrication skills.</p>
                    <a href="#" class="text-blue-600 font-medium hover:text-blue-800 flex items-center">
                        Learn more <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Electrical -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                <div class="h-48 bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-bolt text-6xl text-yellow-600"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Electrical Installation</h3>
                    <p class="text-gray-600 mb-4">Learn proper electrical wiring and installation techniques.</p>
                    <a href="#" class="text-blue-600 font-medium hover:text-blue-800 flex items-center">
                        Learn more <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Computer -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                <div class="h-48 bg-green-100 flex items-center justify-center">
                    <i class="fas fa-laptop text-6xl text-green-600"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Computer Basic Literacy</h3>
                    <p class="text-gray-600 mb-4">Develop fundamental computer skills for work and daily life.</p>
                    <a href="#" class="text-blue-600 font-medium hover:text-blue-800 flex items-center">
                        Learn more <i class="fas fa-chevron-right ml-1"></i>
                    </a>
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