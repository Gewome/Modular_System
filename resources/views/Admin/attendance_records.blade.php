@extends('Admin.layout')

@section('title', 'Attendance Records')

@section('content')
<!-- Header -->
<div class="bg-white shadow-sm border-b mb-6">
    <div class="flex justify-between items-center py-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 px-6">Attendance Records</h1>
            <p class="text-sm text-gray-600 mt-1 px-6">View detailed attendance records per session</p>
        </div>
        <a href="{{ route('admin.attendance') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Mark Attendance
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('admin.attendance.records') }}" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <label for="program_id" class="block text-sm font-medium text-gray-700 mb-1">Program</label>
            <select name="program_id" id="program_id" onchange="this.form.submit()"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ $selectedProgram == $program->id ? 'selected' : '' }}>
                        {{ $program->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<!-- Session Tabs -->
@if($availableSessions->count() > 0)
<div class="bg-white rounded-lg shadow-sm mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            @foreach($availableSessions as $session)
                <a href="{{ route('admin.attendance.records', ['program_id' => $selectedProgram, 'session_number' => $session]) }}"
                   class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $selectedSession == $session ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Session {{ $session }}
                </a>
            @endforeach
        </nav>
    </div>
</div>
@endif

<!-- Attendance Table -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    @if($attendanceRecords->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marked By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OR/Reference Number</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($attendanceRecords as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $record->enrollment->full_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $record->enrollment->program->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($record->enrollment->program)
                                    @php
                                        $schedule = $record->enrollment->program->schedules->first();
                                    @endphp
                                    {{ $schedule ? $schedule->day : 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $record->session_date ? $record->session_date->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($record->enrollment->program)
                                    @php
                                        $schedule = $record->enrollment->program->schedules->first();
                                    @endphp
                                    {{ $schedule ? $schedule->start_time . ' - ' . $schedule->end_time : 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $record->marked_by ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($record->enrollment->program)
                                    ₱{{ number_format($record->enrollment->program->price_per_session, 2) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $record->enrollment->or_number ?? 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance records found</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if($selectedProgram)
                    No attendance records found for the selected program and session.
                @else
                    No attendance records found. Start by marking attendance for students.
                @endif
            </p>
        </div>
    @endif
</div>

<!-- Instructor Information -->
@if($selectedProgram && !empty($instructors))
<div class="bg-white rounded-lg shadow-sm p-6 mt-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Program Instructors</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($instructors as $instructor)
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $instructor }}</p>
                    <p class="text-sm text-gray-500">Program Instructor</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<script>
// Auto-submit form when program is selected
document.getElementById('program_id').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endsection
