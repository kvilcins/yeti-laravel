document.addEventListener('DOMContentLoaded', () => {
    class ModalNotification {
        constructor() {
            this.modal = document.getElementById('modal');
            this.icon = document.getElementById('modalIcon');
            this.message = document.getElementById('modalMessage');
            this.closeBtn = document.getElementById('modalClose');

            this.init();
            this.checkLaravelFlashMessages();
        }

        init = () => {
            this.closeBtn?.addEventListener('click', () => this.hide());
            this.modal?.addEventListener('click', (e) => {
                if (e.target === this.modal) this.hide();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.modal?.classList.contains('show')) {
                    this.hide();
                }
            });
        }

        checkLaravelFlashMessages = () => {
            const successMessage = document.querySelector('meta[name="flash-success"]')?.getAttribute('content');
            const errorMessage = document.querySelector('meta[name="flash-error"]')?.getAttribute('content');

            if (successMessage) this.success(successMessage);
            if (errorMessage) this.error(errorMessage);
        }

        show = (message, type = 'success') => {
            if (!this.modal) return;

            this.message.textContent = message;
            this.icon.className = `modal__icon ${type}`;
            this.icon.textContent = type === 'success' ? '✓' : '✗';
            this.modal.classList.add('show');

            setTimeout(() => this.hide(), 5000);
        }

        hide = () => this.modal?.classList.remove('show');
        success = (message) => this.show(message, 'success');
        error = (message) => this.show(message, 'error');
    }

    window.modalNotification = new ModalNotification();

    const form = document.querySelector('.form');
    if (!form) return;

    const submitButton = form.querySelector('[type="submit"]');
    const photoLabel = form.querySelector('label[for="lot-img"]');
    const photoPreview = form.querySelector('.form__preview img');
    const previewContainer = form.querySelector('.form__preview');
    const inputFileContainer = form.querySelector('.form__file-label');
    const fileInput = form.querySelector('input[type="file"]');

    const isProfileForm = form.id === 'profileForm';

    if (isProfileForm) {
        const fields = {
            name: document.getElementById('name'),
            email: document.getElementById('email'),
            contactDetails: document.getElementById('contact_details'),
            password: document.getElementById('password'),
            passwordConfirm: document.getElementById('password_confirmation'),
            currentPassword: document.getElementById('current_password'),
            currentPasswordForEmail: document.getElementById('current_password_for_email'),
            avatar: document.getElementById('avatar')
        };

        const originalData = {
            name: fields.name?.value.trim() || '',
            email: fields.email?.value.trim() || '',
            contactDetails: fields.contactDetails?.value || ''
        };

        const showHideFields = () => {
            const emailChanged = fields.email?.value.trim() !== originalData.email;
            const emailPasswordGroup = document.getElementById('currentPasswordForEmailGroup');
            if (emailPasswordGroup) {
                emailPasswordGroup.style.display = emailChanged ? 'block' : 'none';
            }

            const hasNewPassword = fields.password?.value.length > 0;
            const currentPasswordGroup = document.getElementById('currentPasswordGroup');
            const confirmPasswordGroup = document.getElementById('password_confirmationGroup');

            if (currentPasswordGroup) {
                currentPasswordGroup.style.display = hasNewPassword ? 'block' : 'none';
            }
            if (confirmPasswordGroup) {
                confirmPasswordGroup.style.display = hasNewPassword ? 'block' : 'none';
            }
        };

        const checkForChanges = () => {
            const hasChanges =
                fields.name?.value.trim() !== originalData.name ||
                fields.email?.value.trim() !== originalData.email ||
                fields.contactDetails?.value !== originalData.contactDetails ||
                fields.password?.value.length > 0 ||
                fields.avatar?.files[0];

            submitButton.disabled = !hasChanges;
            submitButton.classList.toggle('button--disabled', !hasChanges);

            showHideFields();
        };

        const validatePasswords = () => {
            const password = fields.password?.value || '';
            const passwordConfirm = fields.passwordConfirm?.value || '';
            let hasErrors = false;

            const passwordError = document.getElementById('passwordError');
            const passwordConfirmError = document.getElementById('password_confirmationError');
            const passwordGroup = document.getElementById('passwordGroup');
            const passwordConfirmGroup = document.getElementById('password_confirmationGroup');

            if (passwordError) passwordError.textContent = '';
            if (passwordConfirmError) passwordConfirmError.textContent = '';
            if (passwordGroup) passwordGroup.classList.remove('form__item--invalid');
            if (passwordConfirmGroup) passwordConfirmGroup.classList.remove('form__item--invalid');

            if (password && !passwordConfirm) {
                if (passwordConfirmError) passwordConfirmError.textContent = 'Please confirm your password.';
                if (passwordConfirmGroup) passwordConfirmGroup.classList.add('form__item--invalid');
                hasErrors = true;
            }

            if (password && passwordConfirm && password !== passwordConfirm) {
                if (passwordConfirmError) passwordConfirmError.textContent = 'The password confirmation does not match.';
                if (passwordConfirmGroup) passwordConfirmGroup.classList.add('form__item--invalid');
                hasErrors = true;
            }

            return !hasErrors;
        };

        Object.values(fields).forEach(field => {
            if (field) {
                field.addEventListener('input', checkForChanges);
                field.addEventListener('change', checkForChanges);
            }
        });

        fields.password?.addEventListener('blur', validatePasswords);
        fields.passwordConfirm?.addEventListener('blur', validatePasswords);
        fields.passwordConfirm?.addEventListener('input', validatePasswords);

        checkForChanges();

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            if (submitButton.disabled) return;

            if (!validatePasswords()) return;

            document.querySelectorAll('.form__error').forEach(error => error.textContent = '');
            document.querySelectorAll('.form__item--invalid').forEach(item =>
                item.classList.remove('form__item--invalid')
            );

            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken || ''
                }
            })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 422) {
                            return response.json().then(data => {
                                Object.entries(data.errors).forEach(([field, messages]) => {
                                    const errorElement = document.getElementById(field + 'Error');
                                    const groupElement = document.getElementById(field + 'Group');

                                    if (errorElement) errorElement.textContent = messages[0];
                                    if (groupElement) groupElement.classList.add('form__item--invalid');
                                });
                                throw new Error('Validation failed');
                            });
                        }
                        throw new Error('Network error');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.modalNotification.success(data.message);

                        if (data.avatar_url) {
                            const avatarPreview = document.getElementById('avatarPreview');
                            if (avatarPreview) avatarPreview.src = data.avatar_url;
                        }

                        if (fields.password) fields.password.value = '';
                        if (fields.passwordConfirm) fields.passwordConfirm.value = '';
                        if (fields.currentPassword) fields.currentPassword.value = '';
                        if (fields.currentPasswordForEmail) fields.currentPasswordForEmail.value = '';
                        if (fields.avatar) fields.avatar.value = '';

                        originalData.name = fields.name?.value.trim() || '';
                        originalData.email = fields.email?.value.trim() || '';
                        originalData.contactDetails = fields.contactDetails?.value || '';

                        checkForChanges();
                    } else {
                        window.modalNotification.error(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error.message !== 'Validation failed') {
                        window.modalNotification.error('An error occurred while updating profile');
                    }
                });
        });

    } else {
        const checkFormForErrors = () => {
            let hasErrors = false;
            form.querySelectorAll('input, select, textarea').forEach(input => {
                const isValid = input.checkValidity();
                input.classList.toggle('form__item--invalid', !isValid);

                const formItem = input.closest('.form__item');
                if (formItem) {
                    const errorElem = formItem.querySelector('.form__error');
                    if (errorElem) {
                        errorElem.classList.toggle('form__error--visible', !isValid);
                        if (!isValid) {
                            errorElem.textContent = input.validationMessage;
                        } else {
                            errorElem.textContent = '';
                        }
                    }
                }

                if (!isValid) hasErrors = true;
            });

            form.classList.toggle('form--invalid', hasErrors);
            return hasErrors;
        };

        const handleSubmitClick = event => {
            event.preventDefault();

            const hasErrors = checkFormForErrors();

            if (!hasErrors) {
                form.submit();
            } else if (fileInput && !fileInput.value) {
                photoLabel?.classList.remove('hidden');
            }
        };

        submitButton.addEventListener('click', handleSubmitClick);
    }

    const handleFileInputChange = event => {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => {
            if (photoPreview) photoPreview.src = e.target.result;
            if (previewContainer) previewContainer.classList.add('form__preview--visible');
            if (inputFileContainer) inputFileContainer.classList.add('hidden');
            if (photoLabel) photoLabel.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    };

    if (fileInput) fileInput.addEventListener('change', handleFileInputChange);
});
