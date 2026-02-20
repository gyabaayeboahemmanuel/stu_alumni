<!-- Profile Reminder Modal -->
<div id="profileReminderModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay with blur -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" id="modalOverlay"></div>

    <!-- Modal container -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg" 
             id="modalContent"
             style="opacity: 0; transform: scale(0.95) translateY(-20px);">
            
            <!-- Decorative gradient background -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-stu-green via-stu-green-light to-stu-green-dark"></div>
            
            <!-- Modal content -->
            <div class="bg-white px-6 pt-8 pb-6 sm:p-8">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-stu-green to-stu-green-dark mb-4 shadow-lg">
                    <i class="fas fa-user-edit text-3xl text-white"></i>
                </div>
                
                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2" id="modal-title">
                    Complete Your Profile
                </h3>
                
                <!-- Description -->
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Help us build a stronger alumni network! Update your <span class="font-semibold text-stu-green">professional information</span> to connect better with fellow alumni and unlock more opportunities.
                    </p>
                </div>

                <!-- Benefits list -->
                <div class="mt-6 space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-6 w-6 rounded-full bg-green-100">
                                <i class="fas fa-check text-xs text-green-600"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">Better networking opportunities with verified alumni</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-6 w-6 rounded-full bg-green-100">
                                <i class="fas fa-check text-xs text-green-600"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">Increased visibility in the alumni directory</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-6 w-6 rounded-full bg-green-100">
                                <i class="fas fa-check text-xs text-green-600"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">Access to exclusive professional events</p>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('alumni.profile') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-stu-green to-stu-green-dark hover:from-stu-green-dark hover:to-stu-green shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Update Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('profileReminderModal');
    const overlay = document.getElementById('modalOverlay');
    const modalContent = document.getElementById('modalContent');
    
    @if(isset($showProfileReminder) && $showProfileReminder)
        // Show modal with animation
        setTimeout(() => {
            modal.classList.remove('hidden');
            // Trigger animation
            setTimeout(() => {
                modalContent.style.opacity = '1';
                modalContent.style.transform = 'scale(1) translateY(0)';
            }, 10);
        }, 500); // Small delay for better UX
    @endif
});
</script>

