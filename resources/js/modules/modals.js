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
                if (e.target === this.modal) {
                    this.hide();
                }
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

            if (successMessage) {
                this.success(successMessage);
            }

            if (errorMessage) {
                this.error(errorMessage);
            }
        }

        show = (message, type = 'success') => {
            if (!this.modal) return;

            this.message.textContent = message;

            this.icon.className = `modal__icon ${type}`;
            this.icon.textContent = type === 'success' ? '✓' : '✗';

            this.modal.classList.add('show');

            setTimeout(() => {
                this.hide();
            }, 5000);
        }

        hide = () => {
            if (this.modal) {
                this.modal.classList.remove('show');
            }
        }

        success = (message) => {
            this.show(message, 'success');
        }

        error = (message) => {
            this.show(message, 'error');
        }

        showValidationErrors = (errors) => {
            const errorMessages = Object.values(errors).flat();
            this.error(errorMessages.join('\n'));
        }
    }

    window.modalNotification = new ModalNotification();

    const handleAjaxForm = (formSelector) => {
        const form = document.querySelector(formSelector);
        if (!form) return;

        const clearFormErrors = () => {
            document.querySelectorAll('.form__error').forEach(error => {
                error.textContent = '';
            });
            document.querySelectorAll('.form__item--invalid').forEach(item => {
                item.classList.remove('form__item--invalid');
            });
        }

        const showFormValidationErrors = (errors) => {
            for (const field in errors) {
                const errorElement = document.getElementById(field + 'Error');
                const groupElement = document.getElementById(field + 'Group');

                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                }
                if (groupElement) {
                    groupElement.classList.add('form__item--invalid');
                }
            }
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            clearFormErrors();

            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(csrfToken && { 'X-CSRF-TOKEN': csrfToken })
                }
            })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 422) {
                            return response.json().then(data => {
                                showFormValidationErrors(data.errors);
                                throw new Error('Validation failed');
                            });
                        }
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.modalNotification.success(data.message);

                        if (data.avatar_url) {
                            const avatarPreview = document.getElementById('avatarPreview');
                            if (avatarPreview) {
                                avatarPreview.src = data.avatar_url;
                            }
                        }
                    } else {
                        window.modalNotification.error(data.message || 'Произошла ошибка');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    if (error.message !== 'Validation failed') {
                        window.modalNotification.error('Произошла ошибка при отправке формы');
                    }
                });
        });
    }

    const handleAvatarPreview = () => {
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatarPreview');

        if (avatarInput && avatarPreview) {
            avatarInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        avatarPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    handleAjaxForm('#profileForm');
    handleAvatarPreview();

    window.ModalHandler = {
        handleAjaxForm,
        handleAvatarPreview
    };
});
