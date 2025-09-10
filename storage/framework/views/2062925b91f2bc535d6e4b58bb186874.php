<!-- Logo (Collapse Button) -->
<button id="toggleSidebar" class="p-3 flex items-center justify-center border-b border-blue-800 w-full hover:bg-blue-800/80 transition-colors">
    <div class="bg-blue-800 p-1 rounded-lg">
        <img src="<?php echo e(asset('pictures/logo.png')); ?>" alt="Logo" class="w-10 h-10 object-contain">
    </div>
    <span class="logo-text ml-3 font-bold text-xl">Admin Portal</span>
    <div class="ml-auto">
        <i class="fas fa-chevron-left text-blue-300"></i>
    </div>
</button>

<!-- User Profile -->
<button onclick="openProfileModal()" class="user-profile w-full p-4 flex items-center border-b border-blue-800 hover:bg-blue-800/50 transition-colors cursor-pointer">
    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center shadow">
        <?php if(auth()->user()->photo): ?>
            <img src="<?php echo e(asset('storage/' . auth()->user()->photo)); ?>" alt="Profile Photo" class="w-12 h-12 rounded-full object-cover">
        <?php else: ?>
            <i class="fas fa-user text-white"></i>
        <?php endif; ?>
    </div>
    <div class="ml-3 user-details text-left">
        <div class="font-medium text-white"><?php echo e(auth()->user()->name); ?></div>
        <div class="text-xs text-blue-200">OIC, AUXILIARY SERVICES</div>
    </div>
</button>
<div class="flex-1 overflow-y-auto">
    <ul class="py-2 space-y-1">
        <!-- Dashboard -->
        <li style="cursor: pointer;" id="dashboardNav" data-url="<?php echo e(route('admin.dashboard')); ?>" class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 <?php echo e(request()->is('admin/dashboard') ? 'active-nav bg-blue-800/90' : ''); ?>">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-tachometer-alt text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Dashboard</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Enrollment Management -->
        <li style="cursor: pointer;" id="enrollmentNav" data-url="<?php echo e(route('admin.enrollments')); ?>" class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 <?php echo e(request()->is('admin/enrollments') ? 'active-nav bg-blue-800/90' : ''); ?>">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-user-plus text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Enrollment Management</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Enrolled Students -->
        <li style="cursor: pointer;" id="studentsNav" data-url="<?php echo e(route('admin.enrolled.students')); ?>" class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 <?php echo e(request()->is('admin/enrolled-students') ? 'active-nav bg-blue-800/90' : ''); ?>">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-user-graduate text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Enrolled Students</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Attendance -->
        <li style="cursor: pointer;" id="attendanceNav" data-url="<?php echo e(route('admin.attendance')); ?>" class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 <?php echo e(request()->is('admin/attendance') ? 'active-nav bg-blue-800/90' : ''); ?>">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-clipboard-check text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Attendance</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- User Management -->
        <li style="cursor: pointer;" id="userNav" data-url="<?php echo e(route('admin.users')); ?>" class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 <?php echo e(request()->is('admin/users') ? 'active-nav bg-blue-800/90' : ''); ?>">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-users-cog text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">User Management</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>


        <!-- Reports / Monitoring -->
        <li style="cursor: pointer;" id="reportNav" data-url="<?php echo e(route('admin.reports')); ?>" class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 <?php echo e(request()->is('admin/reports') ? 'active-nav bg-blue-800/90' : ''); ?>">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-chart-line text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Reports / Monitoring</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Program & Schedule Settings -->
        <li style="cursor: pointer;" id="scheduleNav" data-url="<?php echo e(route('admin.schedules')); ?>" class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 <?php echo e(request()->is('admin/schedules') ? 'active-nav bg-blue-800/90' : ''); ?>">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-calendar-alt text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Program & Schedule Settings</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Certificates -->
        <li style="cursor: pointer;" id="certificateNav" data-url="<?php echo e(route('admin.certificates')); ?>" class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 <?php echo e(request()->is('admin/certificates') ? 'active-nav bg-blue-800/90' : ''); ?>">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-certificate text-blue-200"></i>
            </div>
            <a class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Certificates</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>
    </ul>
</div>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Admin/partials/navigation.blade.php ENDPATH**/ ?>