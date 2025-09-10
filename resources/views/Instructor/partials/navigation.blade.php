<!-- Logo (Collapse Button) -->
<button id="toggleSidebar" class="p-3 flex items-center justify-center border-b border-blue-800 w-full hover:bg-blue-800/80 transition-colors">
    <div class="bg-blue-800 p-1 rounded-lg">
        <img src="{{ asset('pictures/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
    </div>
    <span class="logo-text ml-3 font-bold text-xl">Instructor Portal</span>
    <div class="ml-auto">
        <i class="fas fa-chevron-left text-blue-300"></i>
    </div>
</button>

<!-- User Profile -->
<div class="user-profile p-4 flex items-center border-b border-blue-800">
    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center shadow">
        <i class="fas fa-user text-white"></i>
    </div>
    <div class="ml-3 user-details">
        <div class="font-medium text-white">{{ ucfirst(auth()->user()->name) }}</div>
        <div class="text-xs text-blue-200">{{ ucfirst(auth()->user()->role) }}</div>
    </div>
</div>
<div class="flex-1 overflow-y-auto">
    <ul class="py-2 space-y-1">
        <!-- Dashboard -->
        <li class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 {{ request()->routeIs('instructor.dashboard') ? 'active-nav bg-blue-800/90' : '' }}">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-tachometer-alt text-blue-200"></i>
            </div>
            <a href="{{ route('instructor.dashboard') }}" class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Dashboard</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Students -->
        <li class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 {{ request()->routeIs('instructor.students*') ? 'active-nav bg-blue-800/90' : '' }}">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-users text-blue-200"></i>
            </div>
            <a href="{{ route('instructor.students') }}" class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Students</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Attendance -->
        <li class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 {{ request()->routeIs('instructor.attendance') ? 'active-nav bg-blue-800/90' : '' }}">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-clipboard-check text-blue-200"></i>
            </div>
            <a href="{{ route('instructor.attendance') }}" class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Attendance</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>

        <!-- Certificate -->
        <li class="nav-item group px-4 py-3 flex items-center hover:bg-blue-800/80 transition-all duration-200 rounded-lg mx-2 {{ request()->routeIs('instructor.certificates') ? 'active-nav bg-blue-800/90' : '' }}">
            <div class="w-8 h-8 flex items-center justify-center bg-blue-700/30 group-hover:bg-blue-600/50 rounded-full transition-all">
                <i class="fas fa-certificate text-blue-200"></i>
            </div>
            <a href="{{ route('instructor.certificates') }}" class="nav-text ml-3 block w-full text-blue-100 group-hover:text-white font-medium">Certificates</a>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right text-xs text-blue-300"></i>
            </div>
        </li>
    </ul>
</div>
