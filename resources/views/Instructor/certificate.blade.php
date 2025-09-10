<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor - Certificates</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }
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
        /* Tab Styles */
        .tab-button.active {
            border-bottom: 2px solid #3b82f6 !important;
            color: #2563eb !important;
        }
        .tab-button:not(.active) {
            border-bottom: 2px solid transparent !important;
            color: #6b7280 !important;
        }

        /* Certificate Preview Modal Styles */
        .certificate-preview-bg {
            background: url('{{ asset('pictures/certificate.png') }}') center/contain no-repeat;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            font-family: 'Inria Serif', serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .certificate-preview-content {
            text-align: center;
            padding: 0px;
            width: 100%;
            max-width: 9in;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .preview-student-name {
            font-family: 'Satisfy', cursive;
            font-size: 38px;
            margin-top: 50px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #b49958;
            text-transform: first-letter: uppercase;
            line-height: 1.2;
        }
        .preview-completion-text {
            font-size: 16px;
            margin-top: 2px;
            color: #7a0000;
        }

    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&family=Satisfy&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-900 text-white w-56 flex flex-col">

            @include('Instructor.partials.navigation')

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
                                <p class="text-sm font-medium text-gray-700">{{ ucfirst(explode(' ', auth()->user()->name)[0]) }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="#" id="logoutButton" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Certificate Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <!-- Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <button id="pendingTab" class="tab-button border-b-2 border-blue-500 py-2 px-1 text-sm font-medium text-blue-600 active">
                                Pending Certificates
                            </button>
                            <button id="issuedTab" class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Issued Certificates
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div id="pendingContent" class="tab-content">
                        <!-- Search and Filter -->
                        <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Search Students</label>
                                <div class="relative">
                                    <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Search by name or ID">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-48">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                                <select id="programFilter" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Programs</option>
                                    @foreach($assignedPrograms as $program)
                                        <option value="{{ $program->name }}">{{ $program->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-48">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Certificate Date</label>
                                <input type="date" id="certificateDate" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Select date">
                            </div>
                        </div>
                        <!-- Student List -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="pendingTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certificate Issued</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($eligibleStudents as $enrollment)
                                    <tr data-name="{{ $enrollment->user->name }}" data-id="{{ $enrollment->user->student_id }}" data-program="{{ $enrollment->program->name ?? 'No Program' }}" data-year="{{ \Carbon\Carbon::parse($enrollment->completion_date)->year }}" data-issued="false">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $enrollment->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->program->name ?? 'No Program' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <input type="checkbox" class="certificate-checkbox" data-id="{{ $enrollment->user->student_id }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900 mr-3 generate-btn"
                                                data-name="{{ $enrollment->user->name }}"
                                                data-program="{{ $enrollment->program->name ?? 'No Program' }}"
                                                data-date="{{ $enrollment->completion_date }}"
                                                data-generated="false">
                                                Generate Certificate
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $eligibleStudents->links() }}
                            </div>
                        </div>
                    </div>

                    <!-- Issued Certificates Tab Content -->
                    <div id="issuedContent" class="tab-content hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="issuedTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="issuedTableBody">
                                    <!-- Issued certificates will be moved here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Certificate Preview Modal (hidden by default) -->
                    <div id="previewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                        <div class="relative mx-auto shadow-lg rounded-md bg-white" style="width: 12in; height: auto; max-width: 95vw; max-height: none; margin: 5vh auto;">
                            <div class="flex justify-between items-center p-4 bg-gray-100 border-b">
                                <h3 class="text-lg font-medium">Certificate Preview</h3>
                                <button onclick="document.getElementById('previewModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="certificate-preview-container" style="width: 100%; height: 8.5in; overflow: hidden;">
                                <div class="certificate-preview-bg" id="certificateToExport" style="width: 11in; height: 8.5in; margin: 0 0.5in;">
                                    <div class="certificate-preview-content">
                                        <h1 class="preview-student-name" id="previewStudentName">[Student Name]</h1>
                                        <p class="preview-completion-text">Has successfully completed the <span id="previewProgramName"><strong>[Program Name]</strong></span></p>
                                        <p class="preview-completion-text">modular training program at Bohol Northern Star College (BNSC), given this day on </p>
                                        <p class="preview-completion-text" id="previewCompletionDate">[Completion Date]</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 p-4 bg-gray-100 border-t">
                                <button id="downloadPdfBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                                    <i class="fas fa-download"></i> Download as PDF
                                </button>
                                <button id="printBtn" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Helper function to capitalize first letter of each word
        function toTitleCase(str) {
            return str.replace(/\w\S*/g, function(txt){
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        // Admin dropdown toggle
        document.getElementById('adminDropdown').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });
        document.addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.add('hidden');
        });
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const contentArea = document.querySelector('.content-area');
            sidebar.classList.toggle('sidebar-collapsed');
            contentArea.classList.toggle('ml-1');
            if (sidebar.classList.contains('sidebar-collapsed')) {
                localStorage.setItem('sidebar-collapsed', 'true');
            } else {
                localStorage.setItem('sidebar-collapsed', 'false');
            }
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-chevron-left');
            icon.classList.toggle('fa-chevron-right');
        });
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                navItems.forEach(nav => nav.classList.remove('active-nav'));
                this.classList.add('active-nav');
                const url = this.getAttribute('data-url');
                if (url) { window.location.href = url; }
            });
        });
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
        handleResize();

        // Tab switching functionality
        document.getElementById('pendingTab').addEventListener('click', function() {
            document.getElementById('pendingContent').classList.remove('hidden');
            document.getElementById('issuedContent').classList.add('hidden');
            document.getElementById('pendingTab').classList.add('active');
            document.getElementById('issuedTab').classList.remove('active');
        });

        document.getElementById('issuedTab').addEventListener('click', function() {
            document.getElementById('pendingContent').classList.add('hidden');
            document.getElementById('issuedContent').classList.remove('hidden');
            document.getElementById('issuedTab').classList.add('active');
            document.getElementById('pendingTab').classList.remove('active');
        });

        // Generate Certificate Button
        document.querySelectorAll('.generate-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const isGenerated = this.dataset.generated === 'true';

                if (!isGenerated) {
                    // First click: Generate certificate with success message
                    this.dataset.generated = 'true';
                    this.textContent = 'Preview Certificate';

                    // Show success message
                    showSuccessMessage(`${this.dataset.name}'s certificate has been generated successfully!`);

                    // Auto-check the certificate issued checkbox
                    const row = this.closest('tr');
                    const checkbox = row.querySelector('.certificate-checkbox');
                    if (checkbox) {
                        checkbox.checked = true;
                        // Move to issued tab after a short delay
                        setTimeout(() => {
                            moveToIssuedTab(row);
                        }, 1000);
                    }
                } else {
                    // Second click: Show preview
                    showCertificatePreview(this);
                }
            });
        });

        // Certificate checkbox functionality
        document.querySelectorAll('.certificate-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const row = this.closest('tr');
                if (this.checked) {
                    moveToIssuedTab(row);
                }
            });
        });

        // Function to show certificate preview
        function showCertificatePreview(btn) {
            document.getElementById('previewStudentName').textContent = toTitleCase(btn.dataset.name);
            document.getElementById('previewProgramName').textContent = btn.dataset.program;

            // Check if certificate date is filled, otherwise use original completion date
            const certificateDateInput = document.getElementById('certificateDate').value;
            let rawDate = certificateDateInput || btn.dataset.date;
            let formattedDate = rawDate;

            if (rawDate) {
                // Handles both 'YYYY-MM-DD' and 'YYYY-MM-DD HH:MM:SS'
                const datePart = rawDate.split(' ')[0];
                const parts = datePart.split('-');
                if (parts.length === 3) {
                    // Month is 0-based in JS Date
                    const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
                    if (!isNaN(dateObj)) {
                        const options = { year: 'numeric', month: 'long', day: 'numeric' };
                        formattedDate = dateObj.toLocaleDateString('en-US', options);
                    }
                }
            }
            document.getElementById('previewCompletionDate').textContent = formattedDate;
            document.getElementById('previewModal').classList.remove('hidden');
        }

        // Function to move row to issued tab
        function moveToIssuedTab(row) {
            const issuedTableBody = document.getElementById('issuedTableBody');
            const newRow = row.cloneNode(true);

            // Update the row for issued tab
            const cells = newRow.querySelectorAll('td');
            if (cells.length >= 4) {
                // Remove checkbox column and update actions
                cells[2].innerHTML = new Date().toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                cells[3].innerHTML = '<button class="text-blue-600 hover:text-blue-900 preview-btn" data-name="' + row.dataset.name + '" data-program="' + row.dataset.program + '" data-date="' + row.dataset.date + '">Preview</button>';
            }

            // Add preview functionality to the new button
            const previewBtn = newRow.querySelector('.preview-btn');
            if (previewBtn) {
                previewBtn.addEventListener('click', function() {
                    showCertificatePreview(this);
                });
            }

            issuedTableBody.appendChild(newRow);
            row.remove();

            // Switch to issued tab
            document.getElementById('issuedTab').click();
        }

        // Function to show success message
        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            successDiv.textContent = message;
            document.body.appendChild(successDiv);

            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        }

        // Download as PDF
        document.getElementById('downloadPdfBtn').addEventListener('click', function() {
            const element = document.getElementById('certificateToExport');
            const opt = {
                margin:       0,
                filename:     'certificate.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
            };
            html2pdf().set(opt).from(element).save();
        });

        // Print
        document.getElementById('printBtn').addEventListener('click', function() {
            const printContents = document.getElementById('certificateToExport').outerHTML;
            const printWindow = window.open('', '', 'width=1100,height=850');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Print Certificate</title>
                    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&family=Satisfy&display=swap" rel="stylesheet">
                    <style>
                        body { margin:0; padding:0; }
                        .certificate-preview-bg { width:11in; height:8.5in; display:flex; justify-content:center; align-items:center; background: url('{{ asset('pictures/certificate.png') }}') center/contain no-repeat; font-family: 'Inria Serif', serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        .certificate-preview-content { text-align:center; width:100%; max-width:9in; }
                        .preview-student-name { font-family:'Satisfy',cursive; font-size:38px; margin-top:50px; margin-bottom:10px; font-weight:bold; color:#b49958; line-height:1.2; }
                        .preview-completion-text { font-size:16px; margin-top:2px; color:#7a0000; }
                    </style>
                </head>
                <body>${printContents}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        });

        // Logout functionality
        document.getElementById('logoutButton').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });

        // Search and Filter
        document.getElementById('searchInput').addEventListener('input', function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('#pendingTable tbody tr').forEach(row => {
                let name = row.getAttribute('data-name').toLowerCase();
                let id = row.getAttribute('data-id').toLowerCase();
                row.style.display = (name.includes(val) || id.includes(val)) ? '' : 'none';
            });
        });
        document.getElementById('programFilter').addEventListener('change', function() {
            let val = this.value;
            document.querySelectorAll('#pendingTable tbody tr').forEach(row => {
                row.style.display = (!val || row.getAttribute('data-program') === val) ? '' : 'none';
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.sidebar');
            const contentArea = document.querySelector('.content-area');
            const toggleIcon = document.querySelector('#toggleSidebar i');
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                sidebar.classList.add('sidebar-collapsed');
                contentArea.classList.remove('ml-1');
                contentArea.classList.add('ml-1');
                if (toggleIcon.classList.contains('fa-chevron-left')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            }
        });
    </script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
    @stack('scripts')
</body>
</html>
