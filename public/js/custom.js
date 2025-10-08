// Custom JavaScript for Surat Online System

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initializeLoadingStates();
    initializeFormValidation();
    initializeFileUpload();
    initializeStatusUpdates();
    initializeTooltips();
    initializeAlerts();
});

// Loading States Management
function initializeLoadingStates() {
    // Create loading overlay
    const loadingOverlay = document.createElement('div');
    loadingOverlay.className = 'loading-overlay';
    loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
    document.body.appendChild(loadingOverlay);

    // Handle form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                showButtonLoading(submitBtn);
            }
            showFormLoading(form);
        });
    });

    // Handle AJAX requests
    if (window.axios) {
        // Add request interceptor
        axios.interceptors.request.use(function (config) {
            showLoadingOverlay();
            return config;
        });

        // Add response interceptor
        axios.interceptors.response.use(
            function (response) {
                hideLoadingOverlay();
                return response;
            },
            function (error) {
                hideLoadingOverlay();
                return Promise.reject(error);
            }
        );
    }
}

function showLoadingOverlay() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) {
        overlay.classList.add('show');
    }
}

function hideLoadingOverlay() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) {
        overlay.classList.remove('show');
    }
}

function showButtonLoading(button) {
    if (button.classList.contains('btn-loading')) return;
    
    button.classList.add('btn-loading');
    button.disabled = true;
    
    const originalText = button.innerHTML;
    button.setAttribute('data-original-text', originalText);
    button.innerHTML = '<span class="btn-text">' + originalText + '</span>';
}

function hideButtonLoading(button) {
    button.classList.remove('btn-loading');
    button.disabled = false;
    
    const originalText = button.getAttribute('data-original-text');
    if (originalText) {
        button.innerHTML = originalText;
        button.removeAttribute('data-original-text');
    }
}

function showFormLoading(form) {
    form.classList.add('form-loading');
}

function hideFormLoading(form) {
    form.classList.remove('form-loading');
}

// Enhanced Form Validation with Real-time Feedback
function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            // Real-time validation with debouncing
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', debounce(function() {
                if (this.classList.contains('is-invalid') || this.value.trim()) {
                    validateField(this);
                }
            }, 300));
            
            // Password confirmation validation
            if (input.name === 'password_confirmation') {
                input.addEventListener('input', debounce(function() {
                    validatePasswordConfirmation(this);
                }, 300));
            }
            
            // Enhanced focus/blur effects
            input.addEventListener('focus', function() {
                this.parentNode.classList.add('field-focused');
                showFieldHint(this);
            });
            
            input.addEventListener('blur', function() {
                this.parentNode.classList.remove('field-focused');
                hideFieldHint(this);
            });
        });
        
        // Form submission validation
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showFormErrors(this);
            }
        });
    });
}

function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');
    const type = field.type;
    const name = field.name;
    
    // Clear previous validation
    clearFieldValidation(field);
    
    let isValid = true;
    let message = '';
    let severity = 'error'; // error, warning, success
    
    // Required validation
    if (isRequired && !value) {
        isValid = false;
        message = getRequiredMessage(field);
    }
    
    // Type-specific validation
    if (value && isValid) {
        const validation = validateByType(field, value);
        isValid = validation.isValid;
        message = validation.message;
        severity = validation.severity || 'error';
    }
    
    // Field-specific validation
    if (value && isValid) {
        const validation = validateByName(field, value);
        isValid = validation.isValid;
        message = validation.message;
        severity = validation.severity || 'error';
    }
    
    // Apply validation result
    applyValidationResult(field, isValid, message, severity);
    
    return isValid;
}

function validateByType(field, value) {
    const type = field.type;
    
    switch (type) {
        case 'email':
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                return { isValid: false, message: 'Format email tidak valid.' };
            }
            break;
            
        case 'password':
            if (value.length < 6) {
                return { isValid: false, message: 'Password minimal 6 karakter.' };
            }
            if (field.name === 'password' && value.length >= 6) {
                const strength = checkPasswordStrength(value);
                if (strength.score < 2) {
                    return { 
                        isValid: true, 
                        message: `Password ${strength.label}. ${strength.suggestion}`,
                        severity: 'warning'
                    };
                }
            }
            break;
            
        case 'tel':
            const phoneRegex = /^[0-9+\-\s()]+$/;
            if (!phoneRegex.test(value)) {
                return { isValid: false, message: 'Format nomor telepon tidak valid.' };
            }
            break;
            
        case 'date':
            const date = new Date(value);
            const today = new Date();
            if (field.name.includes('lahir') && date >= today) {
                return { isValid: false, message: 'Tanggal lahir harus sebelum hari ini.' };
            }
            break;
    }
    
    return { isValid: true, message: '', severity: 'success' };
}

function validateByName(field, value) {
    const name = field.name;
    
    switch (name) {
        case 'nik':
            if (value.length !== 16) {
                return { isValid: false, message: 'NIK harus 16 digit.' };
            }
            if (!/^[0-9]{16}$/.test(value)) {
                return { isValid: false, message: 'NIK hanya boleh berisi angka.' };
            }
            break;
            
        case 'name':
            if (!/^[a-zA-Z\s]+$/.test(value)) {
                return { isValid: false, message: 'Nama hanya boleh berisi huruf dan spasi.' };
            }
            if (value.length < 2) {
                return { isValid: false, message: 'Nama minimal 2 karakter.' };
            }
            break;
            
        case 'no_hp':
            if (value.length < 10 || value.length > 15) {
                return { isValid: false, message: 'Nomor HP harus 10-15 digit.' };
            }
            if (!/^[0-9+\-\s()]+$/.test(value)) {
                return { isValid: false, message: 'Format nomor HP tidak valid.' };
            }
            break;
            
        case 'keperluan':
            if (value.length < 10) {
                return { 
                    isValid: true, 
                    message: 'Keperluan sebaiknya lebih detail (minimal 10 karakter).',
                    severity: 'warning'
                };
            }
            break;
    }
    
    return { isValid: true, message: '', severity: 'success' };
}

function validatePasswordConfirmation(field) {
    const password = document.querySelector('input[name="password"]');
    const confirmation = field.value;
    
    clearFieldValidation(field);
    
    if (confirmation && password) {
        if (password.value !== confirmation) {
            applyValidationResult(field, false, 'Konfirmasi password tidak cocok.', 'error');
            return false;
        } else {
            applyValidationResult(field, true, 'Password cocok.', 'success');
            return true;
        }
    }
    
    return true;
}

function checkPasswordStrength(password) {
    let score = 0;
    let suggestions = [];
    
    if (password.length >= 8) score++;
    else suggestions.push('minimal 8 karakter');
    
    if (/[a-z]/.test(password)) score++;
    else suggestions.push('huruf kecil');
    
    if (/[A-Z]/.test(password)) score++;
    else suggestions.push('huruf besar');
    
    if (/[0-9]/.test(password)) score++;
    else suggestions.push('angka');
    
    if (/[^a-zA-Z0-9]/.test(password)) score++;
    else suggestions.push('karakter khusus');
    
    const labels = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
    const suggestion = suggestions.length > 0 ? `Tambahkan: ${suggestions.join(', ')}` : '';
    
    return {
        score,
        label: labels[score] || 'Sangat Lemah',
        suggestion
    };
}

function validateForm(form) {
    const inputs = form.querySelectorAll('input, textarea, select');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

function clearFieldValidation(field) {
    field.classList.remove('is-invalid', 'is-valid', 'is-warning');
    const feedbacks = field.parentNode.querySelectorAll('.invalid-feedback, .valid-feedback, .warning-feedback');
    feedbacks.forEach(feedback => feedback.remove());
}

function applyValidationResult(field, isValid, message, severity = 'error') {
    clearFieldValidation(field);
    
    if (isValid && severity === 'success') {
        field.classList.add('is-valid');
        if (message) {
            const feedback = createFeedback('valid-feedback', message);
            field.parentNode.appendChild(feedback);
        }
    } else if (isValid && severity === 'warning') {
        field.classList.add('is-warning');
        if (message) {
            const feedback = createFeedback('warning-feedback', message);
            field.parentNode.appendChild(feedback);
        }
    } else if (!isValid) {
        field.classList.add('is-invalid');
        if (message) {
            const feedback = createFeedback('invalid-feedback', message);
            field.parentNode.appendChild(feedback);
        }
    }
}

function createFeedback(className, message) {
    const feedback = document.createElement('div');
    feedback.className = className;
    feedback.innerHTML = `<i class="fas fa-${getFeedbackIcon(className)} me-1"></i>${message}`;
    return feedback;
}

function getFeedbackIcon(className) {
    switch (className) {
        case 'valid-feedback': return 'check-circle';
        case 'warning-feedback': return 'exclamation-triangle';
        case 'invalid-feedback': return 'times-circle';
        default: return 'info-circle';
    }
}

function getRequiredMessage(field) {
    const fieldName = field.getAttribute('data-label') || field.name;
    const messages = {
        'name': 'Nama lengkap wajib diisi.',
        'email': 'Email wajib diisi.',
        'password': 'Password wajib diisi.',
        'nik': 'NIK wajib diisi.',
        'tempat_lahir': 'Tempat lahir wajib diisi.',
        'tanggal_lahir': 'Tanggal lahir wajib diisi.',
        'agama': 'Agama wajib dipilih.',
        'pekerjaan': 'Pekerjaan wajib diisi.',
        'no_hp': 'Nomor HP wajib diisi.',
        'alamat': 'Alamat wajib diisi.',
        'keperluan': 'Keperluan wajib diisi.',
        'jenis_surat_id': 'Jenis surat wajib dipilih.'
    };
    
    return messages[fieldName] || 'Field ini wajib diisi.';
}

function showFieldHint(field) {
    const hints = {
        'nik': 'Masukkan 16 digit NIK sesuai KTP',
        'password': 'Minimal 6 karakter, kombinasi huruf dan angka',
        'email': 'Contoh: nama@email.com',
        'no_hp': 'Contoh: 08123456789',
        'keperluan': 'Jelaskan secara detail keperluan surat'
    };
    
    const hint = hints[field.name];
    if (hint && !field.parentNode.querySelector('.field-hint')) {
        const hintElement = document.createElement('small');
        hintElement.className = 'field-hint text-muted';
        hintElement.innerHTML = `<i class="fas fa-info-circle me-1"></i>${hint}`;
        field.parentNode.appendChild(hintElement);
    }
}

function hideFieldHint(field) {
    const hint = field.parentNode.querySelector('.field-hint');
    if (hint) {
        hint.remove();
    }
}

function showFormErrors(form) {
    const firstInvalidField = form.querySelector('.is-invalid');
    if (firstInvalidField) {
        firstInvalidField.focus();
        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

// Enhanced File Upload with Progress and Preview
function initializeFileUpload() {
    // Skip initialization if page has custom file upload handling
    if (document.querySelector('.upload-area[data-custom-handler="true"]')) {
        return;
    }
    
    const uploadAreas = document.querySelectorAll('.upload-area');
    
    uploadAreas.forEach(area => {
        const fileInput = area.querySelector('input[type="file"]');
        const uploadContent = area.querySelector('#uploadContent');
        const uploadProgress = area.querySelector('#uploadProgress');
        const filePreview = area.querySelector('#filePreview');
        const fileName = area.querySelector('#fileName');
        const fileSize = area.querySelector('#fileSize');
        const removeBtn = area.querySelector('#removeFile');
        const changeFileBtn = area.querySelector('#changeFile');
        
        if (!fileInput) return;
        
        // Check if this area already has event listeners
        if (area.hasAttribute('data-initialized')) return;
        area.setAttribute('data-initialized', 'true');
        
        // Click to upload
        area.addEventListener('click', function(e) {
            if (e.target.id !== 'removeFile' && e.target.id !== 'changeFile') {
                fileInput.click();
            }
        });
        
        // Drag and drop
        area.addEventListener('dragover', function(e) {
            e.preventDefault();
            area.classList.add('dragover', 'border-primary', 'bg-light');
        });
        
        area.addEventListener('dragleave', function(e) {
            e.preventDefault();
            area.classList.remove('dragover', 'border-primary', 'bg-light');
        });
        
        area.addEventListener('drop', function(e) {
            e.preventDefault();
            area.classList.remove('dragover', 'border-primary', 'bg-light');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0]);
            }
        });
        
        // File input change
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });
        
        // Remove file
        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                resetFileUpload();
            });
        }
        
        // Change file
        if (changeFileBtn) {
            changeFileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.click();
            });
        }
        
        function handleFileSelect(file) {
            // Validate file
            const maxSize = 2 * 1024 * 1024; // 2MB
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
            
            if (file.size > maxSize) {
                showAlert('File terlalu besar. Maksimal 2MB.', 'error');
                return;
            }
            
            if (!allowedTypes.includes(file.type)) {
                showAlert('Tipe file tidak didukung. Gunakan PDF, JPG, atau PNG.', 'error');
                return;
            }
            
            // Show progress
            showUploadProgress();
            
            // Simulate upload progress
            simulateUploadProgress(() => {
                // Set the file to input
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                
                // Show preview
                showFilePreview(file);
            });
        }
        
        function showUploadProgress() {
            if (uploadContent) uploadContent.classList.add('d-none');
            if (filePreview) filePreview.classList.add('d-none');
            if (uploadProgress) uploadProgress.classList.remove('d-none');
        }
        
        function simulateUploadProgress(callback) {
            const progressBar = area.querySelector('#uploadProgressBar');
            const uploadStatus = area.querySelector('#uploadStatus');
            let progress = 0;
            
            const interval = setInterval(() => {
                progress += Math.random() * 30;
                if (progress > 100) progress = 100;
                
                if (progressBar) progressBar.style.width = progress + '%';
                
                if (uploadStatus) {
                    if (progress < 30) {
                        uploadStatus.textContent = 'Memvalidasi file...';
                    } else if (progress < 70) {
                        uploadStatus.textContent = 'Mengupload file...';
                    } else if (progress < 100) {
                        uploadStatus.textContent = 'Menyelesaikan upload...';
                    } else {
                        uploadStatus.textContent = 'Upload selesai!';
                        clearInterval(interval);
                        setTimeout(() => {
                            if (uploadProgress) uploadProgress.classList.add('d-none');
                            callback();
                        }, 500);
                    }
                }
            }, 100);
        }
        
        function showFilePreview(file) {
            const fileType = area.querySelector('#fileType');
            const fileIcon = area.querySelector('#fileIcon');
            const imagePreview = area.querySelector('#imagePreview');
            const previewImage = area.querySelector('#previewImage');
            
            // Set file info
            if (fileName) fileName.textContent = file.name;
            if (fileSize) fileSize.textContent = formatFileSize(file.size);
            if (fileType) fileType.textContent = getFileTypeLabel(file.type);
            
            // Handle image preview
            if (file.type.startsWith('image/') && imagePreview && previewImage) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImage.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                    if (fileIcon) fileIcon.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                if (imagePreview) imagePreview.classList.add('d-none');
                if (fileIcon) {
                    fileIcon.classList.remove('d-none');
                    
                    // Set appropriate icon for PDF
                    if (file.type === 'application/pdf') {
                        fileIcon.innerHTML = '<i class="fas fa-file-pdf fa-2x text-danger"></i>';
                    } else {
                        fileIcon.innerHTML = '<i class="fas fa-file fa-2x text-primary"></i>';
                    }
                }
            }
            
            // Show preview
            if (uploadContent) uploadContent.classList.add('d-none');
            if (uploadProgress) uploadProgress.classList.add('d-none');
            if (filePreview) filePreview.classList.remove('d-none');
            
            // Add success border
            area.classList.remove('border-danger');
            area.classList.add('border-success');
        }
        
        function resetFileUpload() {
            fileInput.value = '';
            if (uploadContent) uploadContent.classList.remove('d-none');
            if (uploadProgress) uploadProgress.classList.add('d-none');
            if (filePreview) filePreview.classList.add('d-none');
            area.classList.remove('border-success', 'border-danger', 'border-primary', 'bg-light');
            
            // Reset preview elements
            const imagePreview = area.querySelector('#imagePreview');
            const previewImage = area.querySelector('#previewImage');
            if (imagePreview && previewImage) {
                imagePreview.classList.add('d-none');
                previewImage.src = '';
            }
        }
        
        function getFileTypeLabel(mimeType) {
            const types = {
                'application/pdf': 'PDF Document',
                'image/jpeg': 'JPEG Image',
                'image/jpg': 'JPG Image',
                'image/png': 'PNG Image'
            };
            return types[mimeType] || 'Unknown File Type';
        }
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function getFileTypeLabel(mimeType) {
    const types = {
        'application/pdf': 'PDF Document',
        'image/jpeg': 'JPEG Image',
        'image/jpg': 'JPG Image',
        'image/png': 'PNG Image'
    };
    return types[mimeType] || 'Unknown File Type';
}

// Status Updates with AJAX
function initializeStatusUpdates() {
    const statusForms = document.querySelectorAll('form[action*="status"]');
    
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const url = form.action;
            const method = form.method;
            
            // Show loading with enhanced feedback
            const submitBtn = form.querySelector('button[type="submit"]');
            const statusSelect = form.querySelector('select[name="status"]');
            const keteranganTextarea = form.querySelector('textarea[name="keterangan_status"]');
            
            showButtonLoading(submitBtn);
            showFormProcessing(form);
            
            // Disable form elements during processing
            if (statusSelect) statusSelect.disabled = true;
            if (keteranganTextarea) keteranganTextarea.disabled = true;
            
            // Create progress indicator
            const progressContainer = createProgressIndicator();
            form.appendChild(progressContainer);
            
            // Send AJAX request with timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            
            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                signal: controller.signal
            })
            .then(response => {
                clearTimeout(timeoutId);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // If not JSON, treat as success (redirect response)
                    return { success: true, message: 'Status berhasil diperbarui!' };
                }
            })
            .then(data => {
                hideButtonLoading(submitBtn);
                hideFormProcessing(form);
                progressContainer.remove();
                
                // Re-enable form elements
                if (statusSelect) statusSelect.disabled = false;
                if (keteranganTextarea) keteranganTextarea.disabled = false;
                
                if (data.success) {
                    showAlert(data.message || 'Status berhasil diperbarui!', 'success');
                    
                    // Show success animation
                    form.classList.add('form-success');
                    
                    // Update UI elements if available
                    updateStatusDisplay(data);
                    
                    // Reload page after delay with smooth transition
                    setTimeout(() => {
                        document.body.classList.add('page-transition');
                        setTimeout(() => {
                            window.location.reload();
                        }, 300);
                    }, 2000);
                } else {
                    showAlert(data.message || 'Terjadi kesalahan!', 'error');
                    form.classList.add('form-error');
                    setTimeout(() => form.classList.remove('form-error'), 3000);
                }
            })
            .catch(error => {
                clearTimeout(timeoutId);
                hideButtonLoading(submitBtn);
                hideFormProcessing(form);
                progressContainer.remove();
                
                // Re-enable form elements
                if (statusSelect) statusSelect.disabled = false;
                if (keteranganTextarea) keteranganTextarea.disabled = false;
                
                let errorMessage = 'Terjadi kesalahan jaringan!';
                
                if (error.name === 'AbortError') {
                    errorMessage = 'Request timeout! Silakan coba lagi.';
                } else if (error.message.includes('HTTP error')) {
                    errorMessage = 'Server error! Silakan coba lagi.';
                }
                
                showAlert(errorMessage, 'error');
                form.classList.add('form-error');
                setTimeout(() => form.classList.remove('form-error'), 3000);
                
                console.error('Status update error:', error);
            });
        });
    });
}

// Enhanced form processing indicators
function showFormProcessing(form) {
    form.classList.add('form-processing');
    
    // Add processing overlay to form
    const overlay = document.createElement('div');
    overlay.className = 'form-processing-overlay';
    overlay.innerHTML = `
        <div class="processing-content">
            <div class="processing-spinner"></div>
            <div class="processing-text">Memproses permintaan...</div>
        </div>
    `;
    form.style.position = 'relative';
    form.appendChild(overlay);
}

function hideFormProcessing(form) {
    form.classList.remove('form-processing');
    const overlay = form.querySelector('.form-processing-overlay');
    if (overlay) {
        overlay.remove();
    }
}

function createProgressIndicator() {
    const container = document.createElement('div');
    container.className = 'ajax-progress-container';
    container.innerHTML = `
        <div class="ajax-progress-bar">
            <div class="ajax-progress-fill"></div>
        </div>
        <div class="ajax-progress-text">Mengirim data...</div>
    `;
    
    // Animate progress bar
    setTimeout(() => {
        const fill = container.querySelector('.ajax-progress-fill');
        if (fill) {
            fill.style.width = '70%';
        }
    }, 100);
    
    return container;
}

function updateStatusDisplay(data) {
    // Update status badges if they exist
    const statusBadges = document.querySelectorAll('.status-badge');
    statusBadges.forEach(badge => {
        if (data.newStatus) {
            badge.className = `status-badge status-${data.newStatus}`;
            badge.textContent = getStatusText(data.newStatus);
        }
    });
    
    // Update timeline if it exists
    const timeline = document.querySelector('.status-timeline');
    if (timeline && data.timeline) {
        updateTimeline(timeline, data.timeline);
    }
}

function getStatusText(status) {
    const statusTexts = {
        'diajukan': 'Diajukan',
        'diverifikasi': 'Diverifikasi', 
        'ditandatangani': 'Ditandatangani',
        'selesai': 'Selesai',
        'ditolak': 'Ditolak'
    };
    return statusTexts[status] || status;
}

function updateTimeline(timeline, timelineData) {
    // Update timeline steps based on new status
    const steps = timeline.querySelectorAll('.timeline-step');
    steps.forEach((step, index) => {
        if (timelineData[index]) {
            step.classList.add('completed');
            const date = step.querySelector('.timeline-date');
            if (date && timelineData[index].date) {
                date.textContent = timelineData[index].date;
            }
        }
    });
}

// Initialize Tooltips
function initializeTooltips() {
    // Initialize Bootstrap tooltips if available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

// Alert System
function initializeAlerts() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            fadeOut(alert);
        }, 5000);
    });
    
    // Close button functionality
    const closeButtons = document.querySelectorAll('.alert .btn-close');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const alert = this.closest('.alert');
            fadeOut(alert);
        });
    });
}

function showAlert(message, type = 'info') {
    const alertContainer = document.querySelector('.container') || document.body;
    const alert = document.createElement('div');
    
    const alertClass = type === 'error' ? 'alert-danger' : `alert-${type}`;
    alert.className = `alert ${alertClass} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insert at the beginning of container
    alertContainer.insertBefore(alert, alertContainer.firstChild);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        fadeOut(alert);
    }, 5000);
    
    // Add close functionality
    const closeBtn = alert.querySelector('.btn-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => fadeOut(alert));
    }
}

function fadeOut(element) {
    element.style.transition = 'opacity 0.3s ease';
    element.style.opacity = '0';
    setTimeout(() => {
        if (element.parentNode) {
            element.parentNode.removeChild(element);
        }
    }, 300);
}

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

// Export functions for global use
window.SuratOnline = {
    showAlert,
    showLoadingOverlay,
    hideLoadingOverlay,
    showButtonLoading,
    hideButtonLoading,
    validateField,
    formatFileSize
};