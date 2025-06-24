document.addEventListener('DOMContentLoaded', () => {
    class ModalNotification {
        constructor() {
            this.modal = document.getElementById('modal');
            this.icon = document.getElementById('modalIcon');
            this.message = document.getElementById('modalMessage');
            this.closeBtn = document.getElementById('modalClose');

            this.init();
            this.checkLaravelFlashMessages();
            this.initConfirmModal();
        }

        init = () => {
            this.closeBtn?.addEventListener('click', () => this.hide());
            this.modal?.addEventListener('click', (e) => {
                if (e.target === this.modal) this.hide();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.modal?.classList.contains('modal--show')) {
                    this.hide();
                }
            });
        }

        initConfirmModal = () => {
            this.confirmModal = document.getElementById('confirmModal');
            this.confirmTitle = document.getElementById('confirmTitle');
            this.confirmMessage = document.getElementById('confirmMessage');
            this.confirmOk = document.getElementById('confirmOk');
            this.confirmCancel = document.getElementById('confirmCancel');

            if (!this.confirmModal) return;

            this.confirmCancel.addEventListener('click', () => this.hideConfirm());
            this.confirmModal.addEventListener('click', (e) => {
                if (e.target === this.confirmModal) this.hideConfirm();
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
            this.icon.className = `modal__icon modal__icon--${type}`;
            this.icon.textContent = type === 'success' ? '✓' : '✗';
            this.modal.classList.add('modal--show');

            setTimeout(() => this.hide(), 5000);
        }

        confirm = (title, message, onConfirm, onCancel = null) => {
            if (!this.confirmModal) return;

            this.confirmTitle.textContent = title;
            this.confirmMessage.textContent = message;
            this.confirmModal.classList.add('modal--show');

            const handleConfirm = () => {
                this.hideConfirm();
                this.confirmOk.removeEventListener('click', handleConfirm);
                if (onConfirm) onConfirm();
            };

            const handleCancel = () => {
                this.hideConfirm();
                this.confirmOk.removeEventListener('click', handleConfirm);
                if (onCancel) onCancel();
            };

            this.confirmOk.addEventListener('click', handleConfirm);

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.confirmModal.classList.contains('modal--show')) {
                    handleCancel();
                }
            }, { once: true });
        }

        hide = () => this.modal?.classList.remove('modal--show');
        hideConfirm = () => this.confirmModal?.classList.remove('modal--show');
        success = (message) => this.show(message, 'success');
        error = (message) => this.show(message, 'error');
    }

    window.modalNotification = new ModalNotification();

    const profileForm = document.querySelector('.profile__form');
    const profileNavBtns = document.querySelectorAll('.profile__nav-btn');
    const profilePanels = document.querySelectorAll('.profile__panel');

    if (profileNavBtns.length > 0) {
        profileNavBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const tabName = btn.getAttribute('data-tab');

                profileNavBtns.forEach(b => b.classList.remove('profile__nav-btn--active'));
                profilePanels.forEach(p => p.classList.remove('profile__panel--active'));

                btn.classList.add('profile__nav-btn--active');

                const targetPanel = document.getElementById(tabName + '-tab');
                if (targetPanel) {
                    targetPanel.classList.add('profile__panel--active');
                }
            });
        });
    }

    const deleteButtons = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteButtons.forEach(form => {
        form.removeAttribute('onsubmit');
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            window.modalNotification.confirm(
                'Delete Lot',
                'Are you sure you want to delete this lot? This action cannot be undone.',
                () => {
                    form.submit();
                }
            );
        });
    });

    if (!profileForm) return;

    const submitButton = profileForm.querySelector('[type="submit"]');

    const fields = {
        name: document.getElementById('name'),
        contactDetails: document.getElementById('message'),
        password: document.getElementById('password'),
        passwordConfirm: document.getElementById('password_confirmation'),
        currentPassword: document.getElementById('current_password'),
        avatar: document.getElementById('avatar')
    };

    const originalData = {
        name: fields.name?.value.trim() || '',
        contactDetails: fields.contactDetails?.value || ''
    };

    const showHideFields = () => {
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
            fields.contactDetails?.value !== originalData.contactDetails ||
            fields.password?.value.length > 0 ||
            fields.avatar?.files[0] ||
            avatarMarkedForDeletion;

        submitButton.disabled = !hasChanges;
        submitButton.classList.toggle('profile__submit-btn--disabled', !hasChanges);

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
        if (passwordGroup) passwordGroup.classList.remove('profile__field--invalid');
        if (passwordConfirmGroup) passwordConfirmGroup.classList.remove('profile__field--invalid');

        if (password && !passwordConfirm) {
            if (passwordConfirmError) passwordConfirmError.textContent = 'Please confirm your password.';
            if (passwordConfirmGroup) passwordConfirmGroup.classList.add('profile__field--invalid');
            hasErrors = true;
        }

        if (password && passwordConfirm && password !== passwordConfirm) {
            if (passwordConfirmError) passwordConfirmError.textContent = 'The password confirmation does not match.';
            if (passwordConfirmGroup) passwordConfirmGroup.classList.add('profile__field--invalid');
            hasErrors = true;
        }

        return !hasErrors;
    };

    const getDefaultAvatarUrl = () => {
        const baseUrl = window.location.origin;
        return `${baseUrl}/img/default-avatar.jpg`;
    };

    let avatarMarkedForDeletion = false;

    const handleAvatarDelete = () => {
        const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');

        if (deleteAvatarBtn) {
            deleteAvatarBtn.removeEventListener('click', handleDeleteClick);
            deleteAvatarBtn.addEventListener('click', handleDeleteClick);
        }

        const handleDeleteClick = (e) => {
            e.preventDefault();
            e.stopPropagation();

            window.modalNotification.confirm(
                'Delete Avatar',
                'Are you sure you want to delete your avatar? Changes will be saved when you submit the form.',
                () => {
                    avatarMarkedForDeletion = true;

                    const avatarPreview = document.getElementById('avatarPreview');
                    if (avatarPreview) {
                        avatarPreview.src = getDefaultAvatarUrl();
                        avatarPreview.style.opacity = '0.5';
                        avatarPreview.style.filter = 'grayscale(100%)';
                    }

                    deleteAvatarBtn.style.display = 'none';

                    const restoreBtn = createRestoreButton();
                    deleteAvatarBtn.parentNode.appendChild(restoreBtn);

                    if (fields.avatar) {
                        fields.avatar.value = '';
                    }

                    checkForChanges();
                }
            );
        };

        const createRestoreButton = () => {
            let restoreBtn = document.getElementById('restoreAvatarBtn');
            if (restoreBtn) {
                restoreBtn.remove();
            }

            restoreBtn = document.createElement('button');
            restoreBtn.type = 'button';
            restoreBtn.id = 'restoreAvatarBtn';
            restoreBtn.className = 'profile__avatar-restore';
            restoreBtn.textContent = 'Restore';
            restoreBtn.title = 'Restore avatar';

            restoreBtn.addEventListener('click', (e) => {
                e.preventDefault();
                restoreAvatar();
            });

            return restoreBtn;
        };

        const restoreAvatar = () => {
            avatarMarkedForDeletion = false;

            const avatarPreview = document.getElementById('avatarPreview');
            const deleteBtn = document.getElementById('deleteAvatarBtn');
            const restoreBtn = document.getElementById('restoreAvatarBtn');

            if (avatarPreview) {
                avatarPreview.src = avatarPreview.dataset.originalSrc || avatarPreview.src;
                avatarPreview.style.opacity = '1';
                avatarPreview.style.filter = 'none';
            }

            if (deleteBtn) {
                deleteBtn.style.display = 'inline-block';
            }

            if (restoreBtn) {
                restoreBtn.remove();
            }

            checkForChanges();
        };
    };

    const handleAvatarUpload = () => {
        if (fields.avatar) {
            const avatarPreview = document.getElementById('avatarPreview');
            if (avatarPreview && !avatarPreview.dataset.originalSrc) {
                avatarPreview.dataset.originalSrc = avatarPreview.src;
            }

            fields.avatar.addEventListener('change', (e) => {
                const file = e.target.files[0];

                if (!file) {
                    if (avatarMarkedForDeletion) {
                        const avatarPreview = document.getElementById('avatarPreview');
                        if (avatarPreview) {
                            avatarPreview.src = getDefaultAvatarUrl();
                            avatarPreview.classList.add('profile__avatar-img--deleted');
                        }
                    } else {
                        restoreOriginalAvatar();
                    }
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    window.modalNotification.error('File size must be less than 5MB');
                    e.target.value = '';
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    window.modalNotification.error('Please select a valid image file');
                    e.target.value = '';
                    return;
                }

                avatarMarkedForDeletion = false;

                const reader = new FileReader();
                reader.onload = (event) => {
                    const avatarPreview = document.getElementById('avatarPreview');
                    const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');
                    const restoreBtn = document.getElementById('restoreAvatarBtn');

                    if (avatarPreview) {
                        avatarPreview.src = event.target.result;
                        avatarPreview.classList.remove('profile__avatar-img--deleted');
                    }

                    if (deleteAvatarBtn) {
                        deleteAvatarBtn.style.display = 'inline-block';
                    }

                    if (restoreBtn) {
                        restoreBtn.remove();
                    }
                };
                reader.readAsDataURL(file);

                checkForChanges();
            });
        }

        const restoreOriginalAvatar = () => {
            const avatarPreview = document.getElementById('avatarPreview');
            const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');
            const restoreBtn = document.getElementById('restoreAvatarBtn');

            if (avatarPreview && avatarPreview.dataset.originalSrc) {
                avatarPreview.src = avatarPreview.dataset.originalSrc;
                avatarPreview.style.opacity = '1';
                avatarPreview.style.filter = 'none';
            }

            if (deleteAvatarBtn) {
                deleteAvatarBtn.style.display = 'inline-block';
            }

            if (restoreBtn) {
                restoreBtn.remove();
            }

            avatarMarkedForDeletion = false;
            checkForChanges();
        };
    };

    Object.values(fields).forEach(field => {
        if (field && field !== fields.avatar) {
            field.addEventListener('input', checkForChanges);
            field.addEventListener('change', checkForChanges);
        }
    });

    fields.password?.addEventListener('blur', validatePasswords);
    fields.passwordConfirm?.addEventListener('blur', validatePasswords);
    fields.passwordConfirm?.addEventListener('input', validatePasswords);

    checkForChanges();
    handleAvatarUpload();
    handleAvatarDelete();

    profileForm.addEventListener('submit', (e) => {
        e.preventDefault();

        if (submitButton.disabled) return;

        if (!validatePasswords()) return;

        document.querySelectorAll('.profile__error').forEach(error => error.textContent = '');
        document.querySelectorAll('.profile__field--invalid').forEach(item =>
            item.classList.remove('profile__field--invalid')
        );

        const formData = new FormData(profileForm);

        if (avatarMarkedForDeletion) {
            formData.append('delete_avatar', '1');
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Updating...';

        fetch(profileForm.action, {
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
                                if (groupElement) groupElement.classList.add('profile__field--invalid');
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
                        const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');
                        const restoreBtn = document.getElementById('restoreAvatarBtn');

                        if (avatarPreview) {
                            avatarPreview.src = data.avatar_url;
                            avatarPreview.dataset.originalSrc = data.avatar_url;
                            avatarPreview.classList.remove('profile__avatar-img--deleted');
                        }

                        if (deleteAvatarBtn) {
                            deleteAvatarBtn.style.display = data.avatar_url.includes('default-avatar') ? 'none' : 'inline-block';
                        }

                        if (restoreBtn) {
                            restoreBtn.remove();
                        }
                    }

                    if (fields.password) fields.password.value = '';
                    if (fields.passwordConfirm) fields.passwordConfirm.value = '';
                    if (fields.currentPassword) fields.currentPassword.value = '';
                    if (fields.avatar) fields.avatar.value = '';

                    avatarMarkedForDeletion = false;

                    originalData.name = fields.name?.value.trim() || '';
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
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
    });
});
