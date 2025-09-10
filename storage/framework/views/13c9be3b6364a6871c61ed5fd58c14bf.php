<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin - User Management</title>
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
            
            <?php echo $__env->make('Admin.partials.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
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
                        User Management
                    </h2>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="border-l h-8 border-gray-200"></div>

                    <div class="relative group">
                        <button id="adminDropdown" class="flex items-center focus:outline-none space-x-2">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow">
                                <?php if(auth()->user()->photo): ?>
                                    <img src="<?php echo e(asset('storage/' . auth()->user()->photo)); ?>" alt="Profile Photo" class="w-9 h-9 rounded-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-user text-white"></i>
                                <?php endif; ?>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-700"><?php echo e(auth()->user()->name); ?></p>
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

            <!-- User Management Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg shadow">
                    <!-- User Type Tabs -->
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px">
                            <button id="studentsTab" class="tab-button mr-8 py-4 px-6 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                                Students
                            </button>
                            <button id="instructorsTab" class="tab-button mr-8 py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Instructors
                            </button>
                        </nav>
                    </div>

                    <!-- Tables for each user type -->
                    <div class="p-4">
                        <!-- Students Table -->
                        <div id="studentsTable">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium">Student List</h3>
                                <div class="flex items-center space-x-2">
                                    <input type="text" id="studentSearchInput" placeholder="Search students..." class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button id="studentSearchBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e(ucwords($student->name)); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($student->email); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php if($student->status == 'active'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                                <?php else: ?>
                                                <div class="flex flex-col">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 mb-1">
                                                        Inactive
                                                    </span>
                                                    <?php if($student->scheduled_deletion_at): ?>
                                                    <span class="text-xs text-red-600 countdown-timer" data-deletion-date="<?php echo e($student->scheduled_deletion_at->toISOString()); ?>">
                                                        Deleting in: <span class="font-semibold">-- days</span>
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button class="text-blue-600 hover:text-blue-900 mr-2 edit-user-btn" data-user-id="<?php echo e($student->id); ?>" data-user-type="student">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Instructors Table (hidden by default) -->
                        <div id="instructorsTable" class="hidden">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium">Instructor List</h3>
                                <div class="flex items-center space-x-2">
                                    <input type="text" id="instructorSearchInput" placeholder="Search instructors..." class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button id="instructorSearchBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button id="addInstructorBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        <i class="fas fa-plus mr-2"></i>Add Instructor
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Handled</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e(ucwords($instructor->name)); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($instructor->email); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($instructor->program->name ?? 'No Program'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if($instructor->status == 'active'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                                <?php else: ?>
                                <div class="flex flex-col">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 mb-1">
                                        Inactive
                                    </span>
                                    <?php if($instructor->scheduled_deletion_at): ?>
                                    <span class="text-xs text-red-600 countdown-timer" data-deletion-date="<?php echo e($instructor->scheduled_deletion_at->toISOString()); ?>">
                                        Deleting in: <span class="font-semibold">-- days</span>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button class="text-blue-600 hover:text-blue-900 mr-2 edit-user-btn" data-user-id="<?php echo e($instructor->id); ?>" data-user-type="instructor">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('Admin.instructor_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('Admin.edit_user_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Admin dropdown toggle
            document.getElementById('adminDropdown').addEventListener('click', function(e) {
                e.stopPropagation();
                document.getElementById('dropdownMenu').classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                // Check if click was outside the dropdown
                if (!document.getElementById('adminDropdown').contains(e.target)) {
                    document.getElementById('dropdownMenu').classList.add('hidden');
                }
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
                item.addEventListener('click', function(e) {
                    e.preventDefault();
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
                    document.querySelector('.content-area').classList.add('ml-1');
                } else {
                    document.querySelector('.sidebar').classList.remove('sidebar-collapsed');
                    document.querySelector('.content-area').classList.add('ml-1');
                }
            }

            window.addEventListener('resize', handleResize);
            handleResize(); // Run once on load

            // User Type Tab switching functionality
            const userTypeTabs = ['students', 'instructors'];
            userTypeTabs.forEach(tab => {
                const tabElement = document.getElementById(`${tab}Tab`);
                if (tabElement) {
                    tabElement.addEventListener('click', function() {
                        // Update active tab styling
                        document.querySelectorAll('.tab-button').forEach(btn => {
                            btn.classList.remove('border-blue-500', 'text-blue-600');
                            btn.classList.add('border-transparent', 'text-gray-500');
                        });
                        this.classList.add('border-blue-500', 'text-blue-600');
                        this.classList.remove('border-transparent', 'text-gray-500');

                        // Show/hide tables
                        userTypeTabs.forEach(t => {
                            const table = document.getElementById(`${t}Table`);
                            if (table) {
                                if (t === tab) {
                                    table.classList.remove('hidden');
                                } else {
                                    table.classList.add('hidden');
                                }
                            }
                        });
                    });
                }
            });

            // Logout functionality
            const logoutButton = document.getElementById('logoutButton');
            if (logoutButton) {
                logoutButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('logout-form').submit();
                });
            }

            // Load sidebar state
            const sidebar = document.querySelector('.sidebar');
            const contentArea = document.querySelector('.content-area');
            const toggleIcon = document.querySelector('#toggleSidebar i');

            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                sidebar.classList.add('sidebar-collapsed');
                contentArea.classList.add('ml-1');

                // Set the correct icon direction
                if (toggleIcon && toggleIcon.classList.contains('fa-chevron-left')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                contentArea.classList.remove('ml-1');

                // Set the correct icon direction
                if (toggleIcon && toggleIcon.classList.contains('fa-chevron-right')) {
                    toggleIcon.classList.remove('fa-chevron-right');
                    toggleIcon.classList.add('fa-chevron-left');
                }
            }
            });

    </script>

    <script>
        // Instructor Modal Functionality
        const addInstructorBtn = document.getElementById('addInstructorBtn');
        const addInstructorModal = document.getElementById('addInstructorModal');
        const closeInstructorModal = document.getElementById('closeInstructorModal');
        const cancelInstructorBtn = document.getElementById('cancelInstructorBtn');
        const addInstructorForm = document.getElementById('addInstructorForm');

        // Open instructor modal
        addInstructorBtn.addEventListener('click', function() {
            addInstructorModal.classList.remove('hidden');
        });

        // Close instructor modal
        function closeInstructorModalFunction() {
            addInstructorModal.classList.add('hidden');
            addInstructorForm.reset();
            clearInstructorErrors();
        }

        closeInstructorModal.addEventListener('click', closeInstructorModalFunction);
        cancelInstructorBtn.addEventListener('click', closeInstructorModalFunction);

        // Close instructor modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === addInstructorModal) {
                closeInstructorModalFunction();
            }
        });

        // Clear instructor error messages
        function clearInstructorErrors() {
            document.getElementById('instructorFirstNameError').classList.add('hidden');
            document.getElementById('instructorLastNameError').classList.add('hidden');
            document.getElementById('instructorMiddleNameError').classList.add('hidden');
            document.getElementById('instructorSuffixNameError').classList.add('hidden');
            document.getElementById('instructorProgramHandledError').classList.add('hidden');
            document.getElementById('instructorEmailError').classList.add('hidden');
            document.getElementById('instructorBirthdateError').classList.add('hidden');
            document.getElementById('instructorPasswordError').classList.add('hidden');
        }

        // Instructor form submission
        addInstructorForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Generate username as full name without spaces, lowercase, only first, last, suffix
            const firstName = document.getElementById('instructorFirstName').value.trim();
            const lastName = document.getElementById('instructorLastName').value.trim();
            const suffixName = document.getElementById('instructorSuffixName').value.trim();
            let username = firstName + lastName;
            if (suffixName) {
                username += suffixName;
            }
            username = username.toLowerCase();
            document.getElementById('instructorUsername').value = username;

            // Set password to birthdate
            const birthdate = document.getElementById('instructorBirthdate').value;
            document.getElementById('instructorPassword').value = birthdate;

            const formData = new FormData(addInstructorForm);
            const saveBtn = document.getElementById('saveInstructorBtn');
            const originalText = saveBtn.innerHTML;

            // Disable button and show loading
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            saveBtn.disabled = true;

            try {
                const response = await fetch('<?php echo e(route("admin.users.store")); ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Success - show credentials
                    // Create a detailed success message with credentials
                    const credentialsMessage = `
🎉 Instructor Account Created Successfully!

📋 Account Details:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
👤 Name: ${data.user.name}
👨‍💼 Username: ${data.credentials.username}
🔐 Password: ${data.credentials.password}
📧 Email: ${data.user.email}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

⚠️  IMPORTANT: Please save these credentials securely!
The instructor can use these to log into their account.

Click OK to continue.`;

                    // alert(credentialsMessage);
                    // closeInstructorModalFunction();

                    // Show success modal with credentials
                    document.getElementById('instructor-success-name').textContent = data.user.name;
                    document.getElementById('instructor-success-username').textContent = data.credentials.username;
                    document.getElementById('instructor-success-password').textContent = data.credentials.password;
                    document.getElementById('instructor-success-email').textContent = data.user.email;
                    document.getElementById('instructor-success-modal').classList.remove('hidden');

                    closeInstructorModalFunction();

                    // Reload the page after modal is closed
                    const successModal = document.getElementById('instructor-success-modal');
                    const closeBtn = successModal.querySelector('button');

                    // Function to close success modal and reload
                    function closeSuccessModal() {
                        successModal.classList.add('hidden');
                        location.reload();
                    }

                    closeBtn.addEventListener('click', closeSuccessModal);

                    // Close success modal when clicking outside
                    window.addEventListener('click', function(event) {
                        if (event.target === successModal) {
                            closeSuccessModal();
                        }
                    });


                } else {
                    // Handle validation errors
                    console.log('Validation errors:', data.errors);
                    console.log('Response data:', data);

                    if (data.errors) {
                        if (data.errors.first_name) {
                            document.getElementById('instructorFirstNameError').textContent = data.errors.first_name[0];
                            document.getElementById('instructorFirstNameError').classList.remove('hidden');
                        }
                        if (data.errors.last_name) {
                            document.getElementById('instructorLastNameError').textContent = data.errors.last_name[0];
                            document.getElementById('instructorLastNameError').classList.remove('hidden');
                        }
                        if (data.errors.email) {
                            document.getElementById('instructorEmailError').textContent = data.errors.email[0];
                            document.getElementById('instructorEmailError').classList.remove('hidden');
                        }
                        if (data.errors.password) {
                            document.getElementById('instructorPasswordError').textContent = data.errors.password[0];
                            document.getElementById('instructorPasswordError').classList.remove('hidden');
                        }
                        if (data.errors.birthdate) {
                            document.getElementById('instructorBirthdateError').textContent = data.errors.birthdate[0];
                            document.getElementById('instructorBirthdateError').classList.remove('hidden');
                        }
                        if (data.errors.program_handled) {
                            document.getElementById('instructorProgramHandledError').textContent = data.errors.program_handled[0];
                            document.getElementById('instructorProgramHandledError').classList.remove('hidden');
                        }
                        if (data.errors.role) {
                            document.getElementById('instructorPasswordError').textContent = data.errors.role[0];
                            document.getElementById('instructorPasswordError').classList.remove('hidden');
                        }
                    } else {
                        alert('An error occurred. Please check the console for details.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while creating the instructor. Please try again.');
            } finally {
                // Re-enable button
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        });


    </script>

    <script>
        // Edit User Modal Functionality
        const editUserModal = document.getElementById('editUserModal');
        const closeEditUserModal = document.getElementById('closeEditUserModal');
        const cancelEditUserBtn = document.getElementById('cancelEditUserBtn');
        const editUserForm = document.getElementById('editUserForm');
        const resetPasswordBtn = document.getElementById('resetPasswordBtn');

        // Open edit user modal
        document.querySelectorAll('.edit-user-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const userType = this.getAttribute('data-user-type');

                // Fetch user data via AJAX
                fetch(`/admin/users/${userId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;

                        // Populate modal fields
                        document.getElementById('userFullName').value = user.name.charAt(0).toUpperCase() + user.name.slice(1).toLowerCase();
                        document.getElementById('userUsername').value = user.username;
                        document.getElementById('userRole').value = user.role;
                        document.getElementById('userEmail').value = user.email;
                        document.getElementById('userPhone').value = user.phone || '';
                        document.getElementById('userStatus').value = user.status;
                        document.getElementById('userCreatedAt').value = new Date(user.created_at).toLocaleDateString();
                        document.getElementById('userLastLogin').value = user.last_login ? new Date(user.last_login).toLocaleDateString() : 'Never';

                        // Set form action
                        editUserForm.action = `/admin/users/${userId}`;

                        // Show modal
                        editUserModal.classList.remove('hidden');
                    } else {
                        alert('Error loading user data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading user data');
                });
            });
        });

        // Close edit user modal
        function closeEditUserModalFunction() {
            editUserModal.classList.add('hidden');
            editUserForm.reset();
        }

        closeEditUserModal.addEventListener('click', closeEditUserModalFunction);
        cancelEditUserBtn.addEventListener('click', closeEditUserModalFunction);

        // Close edit user modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === editUserModal) {
                closeEditUserModalFunction();
            }
        });

        // Reset password functionality
        resetPasswordBtn.addEventListener('click', function() {
            // Show password confirmation modal for password reset
            document.getElementById('passwordConfirmTitle').textContent = 'Confirm Password Reset';
            document.getElementById('passwordConfirmMessage').textContent = 'Please enter your password to confirm resetting this user\'s password.';
            document.getElementById('passwordConfirmationModal').classList.remove('hidden');
            document.getElementById('adminPassword').focus();

            // Store the action type for the confirmation handler
            window.pendingAction = 'reset_password';
        });

        // Handle password confirmation form submission
        document.getElementById('passwordConfirmationForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const passwordInput = document.getElementById('adminPassword');
            const passwordError = document.getElementById('passwordError');
            passwordError.classList.add('hidden');
            passwordError.textContent = '';

            const password = passwordInput.value.trim();
            if (!password) {
                passwordError.textContent = 'Password is required.';
                passwordError.classList.remove('hidden');
                return;
            }

            // Verify password via API
            try {
                const response = await fetch('/admin/verify-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ password })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Password verified, proceed with pending action
                    document.getElementById('passwordConfirmationModal').classList.add('hidden');
                    passwordInput.value = '';

                    if (window.pendingAction === 'reset_password') {
                        // Proceed with password reset
                        const userId = editUserForm.action.split('/').pop();

                        const resetResponse = await fetch(`/admin/users/${userId}/reset-password`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        const resetData = await resetResponse.json();

                        if (resetResponse.ok && resetData.success) {
                            document.getElementById('reset-username').textContent = resetData.username;
                            document.getElementById('reset-password').textContent = resetData.new_password;
                            document.getElementById('passwordResetSuccessModal').classList.remove('hidden');
                        } else {
                            alert('Error resetting password: ' + (resetData.message || 'Unknown error'));
                        }
                    } else if (window.pendingAction === 'save_changes') {
                        // Proceed with saving user changes
                        saveUserChanges();
                    }
                } else {
                    passwordError.textContent = data.message || 'Incorrect password.';
                    passwordError.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error verifying password:', error);
                passwordError.textContent = 'An error occurred while verifying password.';
                passwordError.classList.remove('hidden');
            }
        });

        // Save user changes function
        async function saveUserChanges() {
            const formData = new FormData(editUserForm);
            const saveBtn = document.getElementById('saveEditUserBtn');
            const originalText = saveBtn.innerHTML;

            // Disable button and show loading
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            saveBtn.disabled = true;

            try {
                const response = await fetch(editUserForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-HTTP-Method-Override': 'PUT' // Laravel method spoofing for PUT
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    alert('User updated successfully!');
                    closeEditUserModalFunction();
                    location.reload();
                } else {
                    // Handle validation errors
                    console.log('Validation errors:', data.errors);
                    if (data.errors) {
                        let errorMessage = 'Please fix the following errors:\n';
                        Object.values(data.errors).forEach(errorArray => {
                            errorArray.forEach(error => {
                                errorMessage += '• ' + error + '\n';
                            });
                        });
                        alert(errorMessage);
                    } else {
                        alert('An error occurred. Please check the console for details.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the user. Please try again.');
            } finally {
                // Re-enable button
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        }

        // Save changes button click handler
        saveEditUserBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Show password confirmation modal for saving changes
            document.getElementById('passwordConfirmTitle').textContent = 'Confirm Save Changes';
            document.getElementById('passwordConfirmMessage').textContent = 'Please enter your password to confirm saving changes.';
            document.getElementById('passwordConfirmationModal').classList.remove('hidden');
            document.getElementById('adminPassword').focus();

            // Store the action type for the confirmation handler
            window.pendingAction = 'save_changes';
        });

        // Cancel password confirmation modal
        document.getElementById('cancelPasswordConfirm').addEventListener('click', function() {
            document.getElementById('passwordConfirmationModal').classList.add('hidden');
            document.getElementById('adminPassword').value = '';
            document.getElementById('passwordError').classList.add('hidden');
        });

        // Close password confirmation modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('passwordConfirmationModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
                document.getElementById('adminPassword').value = '';
                document.getElementById('passwordError').classList.add('hidden');
            }
        });

        // Countdown timer functionality for inactive users
        function updateCountdownTimers() {
            const countdownElements = document.querySelectorAll('.countdown-timer');

            countdownElements.forEach(element => {
                const deletionDateStr = element.getAttribute('data-deletion-date');
                if (!deletionDateStr) return;

                const deletionDate = new Date(deletionDateStr);
                const now = new Date();
                const timeDiff = deletionDate - now;

                if (timeDiff <= 0) {
                    element.innerHTML = '<span class="font-semibold text-red-700">Deleting soon</span>';
                    return;
                }

                const daysRemaining = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

                if (daysRemaining === 1) {
                    element.innerHTML = 'Deleting in: <span class="font-semibold text-red-700">1 day</span>';
                } else {
                    element.innerHTML = `Deleting in: <span class="font-semibold text-red-700">${daysRemaining} days</span>`;
                }
            });
        }

        // Update countdown timers immediately and then every minute
        updateCountdownTimers();
        setInterval(updateCountdownTimers, 60000); // Update every minute

        // Search functionality for Students table
        const studentSearchInput = document.getElementById('studentSearchInput');
        const studentSearchBtn = document.getElementById('studentSearchBtn');
        const studentsTable = document.getElementById('studentsTable');
        const studentRows = studentsTable.querySelectorAll('tbody tr');

        function filterStudents() {
            const searchTerm = studentSearchInput.value.toLowerCase().trim();

            studentRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        studentSearchBtn.addEventListener('click', filterStudents);

        // Allow Enter key to trigger search for students
        studentSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterStudents();
            }
        });

        // Search functionality for Instructors table
        const instructorSearchInput = document.getElementById('instructorSearchInput');
        const instructorsTable = document.getElementById('instructorsTable');
        const instructorRows = instructorsTable.querySelectorAll('tbody tr');

        function filterInstructors() {
            const searchTerm = instructorSearchInput.value.toLowerCase().trim();

            instructorRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();
                const program = row.cells[2].textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm) || program.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Search on every keystroke for instructors
        instructorSearchInput.addEventListener('input', filterInstructors);

        // Allow search button to trigger search for instructors
        instructorSearchBtn.addEventListener('click', filterInstructors);

        // Allow Enter key to trigger search for instructors
        instructorSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterInstructors();
            }
        });


    </script>

    <!-- Include Admin Profile Modal -->
    <?php echo $__env->make('Admin.Top.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>
</body>
</html><?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Admin/user_management.blade.php ENDPATH**/ ?>