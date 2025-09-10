<!-- Profile Modal Content -->
<div id="profileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60] hidden">
    <div class="text-center mb-4 text-white text-sm italic absolute top-4 left-0 right-0 z-60">
            Click outside to close
    </div>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden profile-tab max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex flex-1 items-center">
                    <div class="relative group mr-6">
                        <?php if(Auth::user()->photo): ?>
                            <img src="<?php echo e(asset('storage/' . Auth::user()->photo)); ?>" alt="Profile" class="w-24 h-24 rounded-full border-4 border-white shadow-md object-cover cursor-pointer hover:opacity-80 transition-opacity duration-300" onclick="document.getElementById('profile-pic-upload').click()">
                        <?php else: ?>
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-md bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center cursor-pointer hover:opacity-80 transition-opacity duration-300" onclick="document.getElementById('profile-pic-upload').click()">
                                <i class="fas fa-user text-white text-3xl"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-black bg-opacity-30 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                            <button class="bg-white text-blue-600 p-2 rounded-full shadow-md hover:bg-blue-50 transition-colors duration-300">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-center md:text-left">
                        <h1 class="text-2xl font-bold"><?php echo e(Auth::user()->name); ?></h1>
                        <p class="text-blue-100"><?php echo e(ucfirst(Auth::user()->role)); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hidden file input for profile picture -->
        <input type="file" id="profile-pic-upload" accept="image/*" class="hidden">

        <!-- Profile Content -->
        <div class="p-6">
            <!-- Admin Information -->
            <div id="about" class="tab-content active">
                <div class="space-y-6">
                    <!-- Top Row: Personal Info and Security side by side -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left: Personal Info -->
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Personal Info</h2>
                            <div class="space-y-6">
                                <!-- Personal Information Section -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-user text-blue-500 mr-3"></i>
                                        Personal Information
                                    </h3>
                                    <div class="space-y-4">
                                        <!-- Gender Section -->
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center flex-1">
                                                <i class="fas fa-venus-mars text-gray-500 mr-3 w-4"></i>
                                                <div>
                                                    <label class="text-sm font-medium text-gray-700">Gender</label>
                                                    <div id="gender-display" class="text-gray-600">
                                                        <?php if(Auth::user()->gender): ?>
                                                            <?php echo e(ucfirst(Auth::user()->gender)); ?>

                                                        <?php else: ?>
                                                            Not specified
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <button onclick="toggleGenderEdit()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                        </div>

                                        <!-- Gender Edit Mode -->
                                        <div id="gender-edit-mode" class="hidden ml-7">
                                            <form id="gender-update-form" class="space-y-3">
                                                <select id="gender-input" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                    <option value="">Select Gender</option>
                                                    <option value="male" <?php echo e(Auth::user()->gender == 'male' ? 'selected' : ''); ?>>Male</option>
                                                    <option value="female" <?php echo e(Auth::user()->gender == 'female' ? 'selected' : ''); ?>>Female</option>
                                                    <option value="other" <?php echo e(Auth::user()->gender == 'other' ? 'selected' : ''); ?>>Other</option>
                                                </select>

                                                <div class="flex space-x-2">
                                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                                        <i class="fas fa-save mr-2"></i>Update
                                                    </button>
                                                    <button type="button" onclick="cancelGenderEdit()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Birthdate Section -->
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center flex-1">
                                                <i class="fas fa-calendar text-gray-500 mr-3 w-4"></i>
                                                <div>
                                                    <label class="text-sm font-medium text-gray-700">Birthdate</label>
                                                    <div id="birthdate-display" class="text-gray-600">
                                                        <?php if(Auth::user()->birthdate): ?>
                                                            <?php echo e(\Carbon\Carbon::parse(Auth::user()->birthdate)->format('M d, Y')); ?>

                                                        <?php else: ?>
                                                            Not specified
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <button onclick="toggleBirthdateEdit()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                        </div>

                                        <!-- Birthdate Edit Mode -->
                                        <div id="birthdate-edit-mode" class="hidden ml-7">
                                            <form id="birthdate-update-form" class="space-y-3">
                                                <input type="date" id="birthdate-input"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                       value="<?php echo e(Auth::user()->birthdate ?? ''); ?>" max="<?php echo e(date('Y-m-d')); ?>">

                                                <div class="flex space-x-2">
                                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                                        <i class="fas fa-save mr-2"></i>Update
                                                    </button>
                                                    <button type="button" onclick="cancelBirthdateEdit()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Phone Section -->
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center flex-1">
                                                <i class="fas fa-phone text-gray-500 mr-3 w-4"></i>
                                                <div>
                                                    <label class="text-sm font-medium text-gray-700">Phone Number</label>
                                                    <div id="phone-display" class="text-gray-600">
                                                        <?php if(Auth::user()->phone): ?>
                                                            <?php echo e(Auth::user()->phone); ?>

                                                        <?php else: ?>
                                                            Not specified
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <button onclick="togglePhoneEdit()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                        </div>

                                        <!-- Phone Edit Mode -->
                                        <div id="phone-edit-mode" class="hidden ml-7">
                                            <form id="phone-update-form" class="space-y-3">
                                                <input type="tel" id="phone-input" placeholder="Enter your phone number (09XXXXXXXXX)"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                       value="<?php echo e(Auth::user()->phone ?? ''); ?>" maxlength="11" pattern="09[0-9]{9}" required>

                                                <div class="flex space-x-2">
                                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                                        <i class="fas fa-save mr-2"></i>Update Phone
                                                    </button>
                                                    <button type="button" onclick="cancelPhoneEdit()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Security -->
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Security</h2>
                            <div class="space-y-6">
                                <!-- Security Section -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-lock text-blue-500 mr-3"></i>
                                        Change Password
                                    </h3>
                                    <form id="password-update-form" class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                            <input type="password" id="current-password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                            <input type="password" id="new-password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                            <input type="password" id="confirm-password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                        </div>
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-save mr-2"></i>Update Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Row: Account Information -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Account Information</h2>
                        <div class="space-y-6">
                            <!-- Account Information Section -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-shield-alt text-blue-500 mr-3"></i>
                                    Account Information
                                </h3>
                                <div class="space-y-4">
                                    <!-- Username Section (Non-editable) -->
                                    <div class="flex items-center">
                                        <i class="fas fa-user-tag text-gray-500 mr-3 w-4"></i>
                                        <div>
                                            <label class="text-sm font-medium text-gray-700">Username</label>
                                            <div class="text-gray-600"><?php echo e(Auth::user()->username ?? 'N/A'); ?></div>
                                        </div>
                                    </div>

                                    <!-- Email Section -->
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center flex-1">
                                            <i class="fas fa-envelope text-gray-500 mr-3 w-4"></i>
                                            <div>
                                                <label class="text-sm font-medium text-gray-700">Email Address</label>
                                                <div id="email-display" class="text-gray-600">
                                                    <?php if(Auth::user()->email): ?>
                                                        <?php echo e(Auth::user()->email); ?>

                                                    <?php else: ?>
                                                        Not specified
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <button onclick="toggleEmailEdit()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                    </div>

                                    <!-- Email Edit Mode -->
                                    <div id="email-edit-mode" class="hidden ml-7">
                                        <form id="email-update-form" class="space-y-3">
                                            <input type="email" id="email-input" placeholder="Enter your email address"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                   value="<?php echo e(Auth::user()->email ?? ''); ?>" required>

                                            <div class="flex space-x-2">
                                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-paper-plane mr-2"></i>Send OTP
                                                </button>
                                                <button type="button" onclick="cancelEmailEdit()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- OTP Verification Mode -->
                                    <div id="otp-verification-mode" class="hidden ml-7">
                                        <div class="bg-blue-50 p-3 rounded-lg mb-3">
                                            <p class="text-sm text-blue-800">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                We've sent a verification code to <strong id="otp-email-display"></strong>
                                            </p>
                                        </div>

                                        <form id="otp-verification-form" class="space-y-3">
                                            <input type="text" id="otp-input" placeholder="Enter 6-digit code"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-lg font-mono"
                                                   maxlength="6" pattern="[0-9]{6}" required>

                                            <div class="flex space-x-2">
                                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                                    <i class="fas fa-check mr-2"></i>Verify & Save
                                                </button>
                                                <button type="button" onclick="resendOTP()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-redo mr-2"></i>Resend OTP
                                                </button>
                                                <button type="button" onclick="cancelOTPVerification()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>

                                        <div class="mt-3 text-center">
                                            <p class="text-sm text-gray-600">
                                                Code expires in <span id="otp-timer" class="font-medium text-blue-600">05:00</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
// Global variables for email editing
let otpCountdownInterval;
let otpExpiryTime;

// Email edit toggle functions and OTP handling
let emailDisplayMode, emailEditMode, otpVerificationMode, emailInput, otpInput, otpEmailDisplay, otpTimer, emailUpdateForm, otpVerificationForm;

// Phone edit toggle functions
let phoneDisplayMode, phoneEditMode, phoneInput, phoneUpdateForm;

// Gender edit toggle functions
let genderDisplay, genderEditMode, genderInput, genderUpdateForm;

// Birthdate edit toggle functions
let birthdateDisplay, birthdateEditMode, birthdateInput, birthdateUpdateForm;

window.openProfileModal = function() {
    console.log('openProfileModal called');

    // Close other modals first
    const studentDetails = document.getElementById('studentDetailsModal');
    if (studentDetails && !studentDetails.classList.contains('hidden')) {
        studentDetails.classList.add('hidden');
    }

    const dropdown = document.getElementById('dropdownMenu');
    if (dropdown) dropdown.classList.add('hidden');

    const modal = document.getElementById('profileModal');
    if (modal) {
        // Ensure modal is visible
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Add a small delay to prevent immediate closing
        setTimeout(() => {
            console.log('Profile modal opened successfully');
            console.log('Modal visibility:', window.getComputedStyle(modal).display);
            console.log('Modal z-index:', window.getComputedStyle(modal).zIndex);
        }, 100);
    } else {
        console.error('Profile modal not found!');
    }
}

window.closeProfileModal = function() {
    const modal = document.getElementById('profileModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        console.log('Profile modal closed');
    }
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

function toggleEmailEdit() {
    if (!emailDisplayMode || !emailEditMode || !otpVerificationMode) return;
    emailDisplayMode.classList.add('hidden');
    emailEditMode.classList.remove('hidden');
    otpVerificationMode.classList.add('hidden');
}

function cancelEmailEdit() {
    if (!emailEditMode || !emailDisplayMode || !otpVerificationMode || !emailInput) return;
    emailEditMode.classList.add('hidden');
    emailDisplayMode.classList.remove('hidden');
    otpVerificationMode.classList.add('hidden');
    emailInput.value = '<?php echo e(Auth::user()->email ?? ''); ?>';
}

function startOTPTimer(duration) {
    clearInterval(otpCountdownInterval);
    otpExpiryTime = Date.now() + duration * 1000;

    otpCountdownInterval = setInterval(() => {
        if (!otpTimer) return;
        const remaining = Math.max(0, otpExpiryTime - Date.now());
        const minutes = Math.floor(remaining / 60000);
        const seconds = Math.floor((remaining % 60000) / 1000);
        otpTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        if (remaining <= 0) {
            clearInterval(otpCountdownInterval);
            alert('OTP expired. Please resend the code.');
        }
    }, 1000);
}

function resendOTP() {
    if (!emailInput) return;
    const email = emailInput.value.trim();
    if (!email) {
        alert('Please enter a valid email address to resend OTP.');
        return;
    }

    fetch('<?php echo e(route("admin.email.send_otp")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
        },
        body: JSON.stringify({ email }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('OTP resent successfully!');
            startOTPTimer(300);
        } else {
            alert(data.message || 'Failed to resend OTP. Please try again.');
        }
    })
    .catch(() => {
        alert('An error occurred while resending OTP. Please try again.');
    });
}

function cancelOTPVerification() {
    if (!otpVerificationMode || !emailEditMode || !otpInput) return;
    otpVerificationMode.classList.add('hidden');
    emailEditMode.classList.remove('hidden');
    otpInput.value = '';
}

function togglePhoneEdit() {
    if (!phoneDisplayMode || !phoneEditMode) return;
    phoneDisplayMode.classList.add('hidden');
    phoneEditMode.classList.remove('hidden');
}

function cancelPhoneEdit() {
    if (!phoneEditMode || !phoneDisplayMode || !phoneInput) return;
    phoneEditMode.classList.add('hidden');
    phoneDisplayMode.classList.remove('hidden');
    phoneInput.value = '<?php echo e(Auth::user()->phone ?? ''); ?>';
}

// Gender edit functions
function toggleGenderEdit() {
    const genderDisplay = document.getElementById('gender-display');
    const genderEditMode = document.getElementById('gender-edit-mode');
    if (!genderDisplay || !genderEditMode) return;

    genderDisplay.classList.add('hidden');
    genderEditMode.classList.remove('hidden');
}

function cancelGenderEdit() {
    const genderDisplay = document.getElementById('gender-display');
    const genderEditMode = document.getElementById('gender-edit-mode');
    const genderInput = document.getElementById('gender-input');
    if (!genderEditMode || !genderDisplay || !genderInput) return;

    genderEditMode.classList.add('hidden');
    genderDisplay.classList.remove('hidden');
    genderInput.value = '<?php echo e(Auth::user()->gender ?? ''); ?>';
}

// Birthdate edit functions
function toggleBirthdateEdit() {
    const birthdateDisplay = document.getElementById('birthdate-display');
    const birthdateEditMode = document.getElementById('birthdate-edit-mode');
    if (!birthdateDisplay || !birthdateEditMode) return;

    birthdateDisplay.classList.add('hidden');
    birthdateEditMode.classList.remove('hidden');
}

function cancelBirthdateEdit() {
    const birthdateDisplay = document.getElementById('birthdate-display');
    const birthdateEditMode = document.getElementById('birthdate-edit-mode');
    const birthdateInput = document.getElementById('birthdate-input');
    if (!birthdateEditMode || !birthdateDisplay || !birthdateInput) return;

    birthdateEditMode.classList.add('hidden');
    birthdateDisplay.classList.remove('hidden');
    birthdateInput.value = '<?php echo e(Auth::user()->birthdate ?? ''); ?>';
}

document.addEventListener('DOMContentLoaded', function() {
    const profileModal = document.getElementById('profileModal');
    if (!profileModal) return;

    // Initialize global variables
    emailDisplayMode = document.getElementById('email-display-mode');
    emailEditMode = document.getElementById('email-edit-mode');
    otpVerificationMode = document.getElementById('otp-verification-mode');
    emailInput = document.getElementById('email-input');
    otpInput = document.getElementById('otp-input');
    otpEmailDisplay = document.getElementById('otp-email-display');
    otpTimer = document.getElementById('otp-timer');
    emailUpdateForm = document.getElementById('email-update-form');
    otpVerificationForm = document.getElementById('otp-verification-form');

    // Initialize phone variables
    phoneDisplayMode = document.getElementById('phone-display-mode');
    phoneEditMode = document.getElementById('phone-edit-mode');
    phoneInput = document.getElementById('phone-input');
    phoneUpdateForm = document.getElementById('phone-update-form');

    // Add input validation to allow only numbers in phone input
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove any non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        phoneInput.addEventListener('keypress', function(e) {
            // Prevent non-numeric key presses
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                e.preventDefault();
            }
        });
    }

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

        // Add event listener for file selection
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select a valid image file.');
                    return;
                }

                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB.');
                    return;
                }

                // Create FormData and send file
                const formData = new FormData();
                formData.append('profile_photo', file);

                // Show loading state
                const originalImage = profileImg || profilePlaceholder;
                if (profileImg) {
                    profileImg.style.opacity = '0.5';
                } else if (profilePlaceholder) {
                    profilePlaceholder.style.opacity = '0.5';
                }

                fetch('<?php echo e(route("admin.profile.photo.update")); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the profile image
                        const newImagePath = '<?php echo e(asset("storage/")); ?>/' + data.path;
                        if (profileImg) {
                            profileImg.src = newImagePath;
                            profileImg.style.opacity = '1';
                            // Ensure proper sizing
                            profileImg.className = 'w-24 h-24 rounded-full border-4 border-white shadow-md object-cover cursor-pointer hover:opacity-80 transition-opacity duration-300';
                        } else if (profilePlaceholder) {
                            // Replace placeholder with actual image
                            const newImg = document.createElement('img');
                            newImg.src = newImagePath;
                            newImg.alt = 'Profile';
                            newImg.className = 'w-24 h-24 rounded-full border-4 border-white shadow-md bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center cursor-pointer hover:opacity-80 transition-opacity duration-300';
                            newImg.style.width = '96px';
                            newImg.style.height = '96px';
                            newImg.style.borderRadius = '50%';
                            newImg.style.objectFit = 'cover';
                            newImg.onclick = function() { document.getElementById('profile-pic-upload').click(); };
                            profilePlaceholder.parentNode.replaceChild(newImg, profilePlaceholder);
                        }

                        alert('Profile photo updated successfully!');
                    } else {
                        // Reset opacity
                        if (profileImg) {
                            profileImg.style.opacity = '1';
                        } else if (profilePlaceholder) {
                            profilePlaceholder.style.opacity = '1';
                        }
                        alert(data.message || 'Failed to update profile photo.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Reset opacity
                    if (profileImg) {
                        profileImg.style.opacity = '1';
                    } else if (profilePlaceholder) {
                        profilePlaceholder.style.opacity = '1';
                    }
                    alert('An error occurred while updating the profile photo.');
                });
            }
        });
    }

    if (emailUpdateForm) {
        emailUpdateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = emailInput.value.trim();

            if (!email) {
                alert('Please enter a valid email address.');
                return;
            }

            // Send OTP to the entered email
            fetch('<?php echo e(route("admin.email.send_otp")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                },
                body: JSON.stringify({ email }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    otpEmailDisplay.textContent = email;
                    emailEditMode.classList.add('hidden');
                    otpVerificationMode.classList.remove('hidden');
                    startOTPTimer(300); // 5 minutes
                } else {
                    alert(data.message || 'Failed to send OTP. Please try again.');
                }
            })
            .catch(() => {
                alert('An error occurred while sending OTP. Please try again.');
            });
        });
    }

    if (otpVerificationForm) {
        otpVerificationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const otp = otpInput.value.trim();
            const email = emailInput.value.trim();

            if (!otp || otp.length !== 6) {
                alert('Please enter a valid 6-digit OTP code.');
                return;
            }

            // Verify OTP
            fetch('<?php echo e(route("admin.email.verify_otp")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                },
                body: JSON.stringify({ email, otp }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Email updated successfully!');
                    // Reload page or update UI accordingly
                    location.reload();
                } else {
                    alert(data.message || 'OTP verification failed. Please try again.');
                }
            })
            .catch(() => {
                alert('An error occurred during OTP verification. Please try again.');
            });
        });
    }

    if (phoneUpdateForm) {
        phoneUpdateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const phone = phoneInput.value.trim();

            if (!phone) {
                alert('Please enter a phone number.');
                return;
            }

            // Validate phone number format (must start with 09 and be 11 digits)
            const phoneRegex = /^09\d{9}$/;
            if (!phoneRegex.test(phone)) {
                alert('Phone number must start with "09" and be exactly 11 digits long (e.g., 09123456789).');
                return;
            }

            // Update phone number
            fetch('<?php echo e(route("admin.phone.update")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                },
                body: JSON.stringify({ phone }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Phone number updated successfully!');
                    // Reload page or update UI accordingly
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update phone number. Please try again.');
                }
            })
            .catch(() => {
                alert('An error occurred while updating phone number. Please try again.');
            });
        });
    }

    // Initialize gender variables
    genderDisplay = document.getElementById('gender-display');
    genderEditMode = document.getElementById('gender-edit-mode');
    genderInput = document.getElementById('gender-input');
    genderUpdateForm = document.getElementById('gender-update-form');

    // Initialize birthdate variables
    birthdateDisplay = document.getElementById('birthdate-display');
    birthdateEditMode = document.getElementById('birthdate-edit-mode');
    birthdateInput = document.getElementById('birthdate-input');
    birthdateUpdateForm = document.getElementById('birthdate-update-form');

    if (genderUpdateForm) {
        genderUpdateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const gender = genderInput.value.trim();

            if (!gender) {
                alert('Please select a gender.');
                return;
            }

            // Update gender
            fetch('<?php echo e(route("admin.gender.update")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                },
                body: JSON.stringify({ gender }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Gender updated successfully!');
                    // Reload page or update UI accordingly
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update gender. Please try again.');
                }
            })
            .catch(() => {
                alert('An error occurred while updating gender. Please try again.');
            });
        });
    }

    if (birthdateUpdateForm) {
        birthdateUpdateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const birthdate = birthdateInput.value.trim();

            if (!birthdate) {
                alert('Please select a birthdate.');
                return;
            }

            // Validate birthdate is not in the future
            const selectedDate = new Date(birthdate);
            const today = new Date();
            if (selectedDate > today) {
                alert('Birthdate cannot be in the future.');
                return;
            }

            // Update birthdate
            fetch('<?php echo e(route("admin.birthdate.update")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                },
                body: JSON.stringify({ birthdate }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Birthdate updated successfully!');
                    // Reload page or update UI accordingly
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update birthdate. Please try again.');
                }
            })
            .catch(() => {
                alert('An error occurred while updating birthdate. Please try again.');
            });
        });
    }
});
</script>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/Admin/Top/profile.blade.php ENDPATH**/ ?>