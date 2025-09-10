<!-- Profile Modal Content -->
<div id="profileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="text-center mb-4 text-white text-sm italic absolute top-4 left-0 right-0 z-60">
        Click outside to close
    </div>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden profile-tab max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex flex-1 items-center">
                    <div class="relative group mr-6">
                        @if($enrollment && $enrollment->photo)
                            <img src="{{ asset('storage/' . $enrollment->photo) }}" alt="Profile" class="w-24 h-24 rounded-full border-4 border-white shadow-md object-cover cursor-pointer" style="z-index: 2; position: relative;" onclick="document.getElementById('profile-pic-upload').click()">
                        @elseif($student && $student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Profile" class="w-24 h-24 rounded-full border-4 border-white shadow-md object-cover cursor-pointer" style="z-index: 2; position: relative;" onclick="document.getElementById('profile-pic-upload').click()">
                        @else
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-md bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center cursor-pointer" style="z-index: 2; position: relative;" onclick="document.getElementById('profile-pic-upload').click()">
                                <i class="fas fa-user text-white text-3xl"></i>
                            </div>
                        @endif
                        <span class="absolute bottom-0 right-0 bg-green-500 rounded-full w-6 h-6 border-2 border-white flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </span>
                        <div class="absolute inset-0 bg-black bg-opacity-30 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="z-index: 1; pointer-events: none;">
                            <button class="bg-white text-blue-600 p-2 rounded-full shadow-md hover:bg-blue-50 transition" style="pointer-events: auto;">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-center md:text-left">
                        <h1 class="text-2xl font-bold">
                            @if($enrollment)
                                {{ $enrollment->last_name ?? '' }}{{ $enrollment->last_name && $enrollment->first_name ? ', ' : '' }}{{ $enrollment->first_name ?? '' }}{{ $enrollment->middle_name ? ' ' . $enrollment->middle_name : '' }}{{ $enrollment->suffix_name ? ' ' . $enrollment->suffix_name : '' }}
                            @else
                                {{ $student->name ?? 'Student' }}
                            @endif
                        </h1>
                        <p class="text-blue-100">{{ $student->role ?? 'Student' }}</p>
                        <div class="flex justify-center md:justify-start mt-2 space-x-2">
                            <span class="bg-blue-400 bg-opacity-30 px-3 py-1 rounded-full text-sm">{{ $enrollment->program->name ?? 'No Program' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden file input for profile picture -->
        <input type="file" id="profile-pic-upload" accept="image/*" class="hidden">

        <!-- Profile Content -->
        <div class="p-6">
            <!-- Student Information -->
            <div id="about" class="tab-content active">
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Personal Info</h2>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i class="fas fa-envelope text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">{{ $student->email ?? 'No email' }}</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">{{ $enrollment->phone ?? ($student->phone ?? 'No phone number') }}</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">{{ $enrollment->address ?? ($student->address ?? 'No address') }}</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-birthday-cake text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Birthdate: <span class="font-medium">{{ $enrollment->birthdate ? \Carbon\Carbon::parse($enrollment->birthdate)->format('M d, Y') : 'Not specified' }}</span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-user-clock text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Age: <span class="font-medium">{{ $enrollment->age ?? 'Not specified' }}</span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-calendar-alt text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Member since: <span class="font-medium">{{ $student->created_at->format('M d, Y') ?? 'N/A' }}</span></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Additional Personal Information -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Additional Information</h2>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i class="fas fa-venus-mars text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Gender: <span class="font-medium">{{ $enrollment->gender ?? 'Not specified' }}</span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-ring text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Civil Status: <span class="font-medium">{{ $enrollment->civil_status ?? 'Not specified' }}</span></span>
                            </li>
                            @if($enrollment->civil_status == 'Married')
                            <li class="flex items-center">
                                <i class="fas fa-heart text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Spouse: <span class="font-medium">{{ $enrollment->spouse_name ?? 'Not specified' }}</span></span>
                            </li>
                            @endif
                            <li class="flex items-center">
                                <i class="fas fa-globe text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Citizenship: <span class="font-medium">{{ $enrollment->citizenship ?? 'Not specified' }}</span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-pray text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Religion: <span class="font-medium">{{ $enrollment->religion ?? 'Not specified' }}</span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-map-pin text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Place of Birth: <span class="font-medium">{{ $enrollment->place_of_birth ?? 'Not specified' }}</span></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Family Information -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Family Information</h2>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i class="fas fa-male text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Father: <span class="font-medium">{{ $enrollment->father_name ?? 'Not specified' }}</span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-female text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Mother: <span class="font-medium">{{ $enrollment->mother_name ?? 'Not specified' }}</span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-user-shield text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Guardian: <span class="font-medium">{{ $enrollment->guardian ?? 'Not specified' }}</span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-home text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Home Address: <span class="font-medium">{{ $enrollment->address ?? ($student->address ?? 'Not specified') }}</span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone-alt text-blue-500 mr-3 w-5"></i>
                                <span class="text-gray-600">Guardian Contact: <span class="font-medium">{{ $enrollment->guardian_contact ?? 'Not specified' }}</span></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openProfileModal() {
    document.getElementById('profileModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeProfileModal() {
    document.getElementById('profileModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('profileModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProfileModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProfileModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const profileModal = document.getElementById('profileModal');
    if (!profileModal) return;
    
    const uploadBtn = profileModal.querySelector('.relative.group button');
    const profileContainer = profileModal.querySelector('.relative.group');
    const fileInput = document.getElementById('profile-pic-upload');
    
    // Make profile photo clickable - only within the modal
    const profileImg = profileModal.querySelector('.relative.group img');
    const profilePlaceholder = profileModal.querySelector('.relative.group > div:not(.absolute)');
    
    if (profileImg) {
        profileImg.addEventListener('click', function(e) {
            console.log('Profile image clicked in modal');
            fileInput.click();
        });
    }
    
    if (profilePlaceholder) {
        profilePlaceholder.addEventListener('click', function(e) {
            console.log('Profile placeholder clicked in modal');
            fileInput.click();
        });
    }
    
    // Also keep container click for fallback - only within modal
    if (profileContainer) {
        profileContainer.addEventListener('click', function(e) {
            console.log('Profile container clicked in modal', e.target);
            if (e.target.closest('button')) {
                console.log('Button clicked, ignoring');
                return; // Don't trigger if clicking the camera button
            }
            console.log('Triggering file input click from modal');
            fileInput.click();
        });
    }
    
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            fileInput.click();
        });
    }
    
    fileInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            const profileImg = profileModal.querySelector('.relative.group img');
            
            reader.onload = function(event) {
                if (profileImg) {
                    profileImg.src = event.target.result;
                }
                
                // Upload the file to server
                const formData = new FormData();
                formData.append('profile_photo', e.target.files[0]);
                formData.append('_token', '{{ csrf_token() }}');
                
                fetch('{{ route("student.profile.photo.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('Profile photo updated successfully!', 'success');
                    } else {
                        // Show error message
                        showNotification(data.message || 'Failed to update profile photo', 'error');
                        // Revert to original image if upload failed
                        if (profileImg) {
                            profileImg.src = '{{ $student->photo ? asset("storage/" . $student->photo) : "" }}';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred while uploading the photo', 'error');
                    // Revert to original image
                    if (profileImg) {
                        profileImg.src = '{{ $student->photo ? asset("storage/" . $student->photo) : "" }}';
                    }
                });
            };
            
            reader.readAsDataURL(e.target.files[0]);
        }
    });
});

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
