document.addEventListener('DOMContentLoaded', () => {
    class ImageUploadHandler {
        constructor(inputSelector) {
            this.input = document.querySelector(inputSelector);
            if (!this.input) return;

            const formItem = this.input.closest('.form__item, .profile__field');
            if (!formItem) return;

            this.preview = formItem.querySelector('.form__preview, .profile__avatar');
            this.previewImg = this.preview?.querySelector('.form__preview-img, .profile__avatar-img');
            this.deleteBtn = this.preview?.querySelector('.form__delete-btn, .profile__avatar-delete');

            this.isAvatarField = this.input.id === 'avatar';
            this.isLotImageField = this.input.id === 'lot_img';
            this.isProfileForm = !!this.input.closest('.profile__form');
            this.isRegistrationForm = !!this.input.closest('.form') && !this.input.closest('.profile__form');

            this.defaultAvatarUrl = '/img/default-avatar.jpg';

            this.init();
        }

        init() {
            if (!this.input || !this.preview || !this.previewImg) return;

            this.input.addEventListener('change', this.handleFileSelect.bind(this));

            if (this.deleteBtn && (!this.isProfileForm || this.isRegistrationForm)) {
                this.deleteBtn.addEventListener('click', this.handleDelete.bind(this));

                if (this.isLotImageField && this.preview?.classList.contains('form__preview--visible')) {
                    this.deleteBtn.style.display = 'flex';
                }
            }
        }

        handleFileSelect(e) {
            const file = e.target.files[0];

            if (!file) {
                const isPreviewVisible = this.isAvatarField ?
                    !this.preview?.classList.contains('profile__avatar--hidden') :
                    this.preview?.classList.contains('form__preview--visible');

                if (!isPreviewVisible) {
                    this.clearPreview();
                }
                return;
            }

            if (!this.validateFile(file)) {
                e.target.value = '';
                return;
            }

            this.displayPreview(file);

            if (this.isLotImageField) {
                const deleteImageInput = document.getElementById('delete_image');
                if (deleteImageInput) {
                    deleteImageInput.value = '0';
                }
            }
        }

        validateFile(file) {
            const maxSize = this.isAvatarField ? 5 * 1024 * 1024 : 2 * 1024 * 1024;

            if (file.size > maxSize) {
                const sizeMB = maxSize / (1024 * 1024);
                this.showError(`File size must be less than ${sizeMB}MB`);
                return false;
            }

            if (!file.type.startsWith('image/')) {
                this.showError('Please select a valid image file');
                return false;
            }

            return true;
        }

        displayPreview(file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                if (this.previewImg) {
                    this.previewImg.src = event.target.result;
                }

                if (this.isAvatarField) {
                    this.preview?.classList.remove('profile__avatar--hidden');
                    this.deleteBtn?.classList.remove('profile__avatar-delete--hidden');
                } else if (this.isLotImageField) {
                    this.preview?.classList.add('form__preview--visible');
                    this.createOrShowDeleteBtn();
                }
            };
            reader.readAsDataURL(file);
        }

        createOrShowDeleteBtn() {
            if (!this.deleteBtn && this.isLotImageField) {
                this.deleteBtn = document.createElement('button');
                this.deleteBtn.type = 'button';
                this.deleteBtn.className = 'form__delete-btn';
                this.deleteBtn.innerHTML = '×';
                this.deleteBtn.title = 'Delete image';
                this.deleteBtn.addEventListener('click', this.handleDelete.bind(this));

                this.preview.style.position = 'relative';
                this.preview.appendChild(this.deleteBtn);
            }

            if (this.deleteBtn && this.isLotImageField) {
                this.deleteBtn.style.display = 'flex';
            }
        }

        handleDelete(e) {
            e.preventDefault();

            if (this.isAvatarField && this.isRegistrationForm) {
                this.handleFormDelete();
            } else if (this.isLotImageField) {
                this.handleLotImageDelete();
            } else {
                this.handleFormDelete();
            }
        }

        handleProfileAvatarDelete() {
            return;
        }

        handleLotImageDelete() {
            const confirmMessage = 'Are you sure you want to delete this image?';

            if (window.modalNotification) {
                window.modalNotification.confirm(
                    'Delete Image',
                    confirmMessage,
                    () => {
                        this.removeLotImage();
                    }
                );
            } else {
                if (confirm(confirmMessage)) {
                    this.removeLotImage();
                }
            }
        }

        removeLotImage() {
            const deleteImageInput = document.getElementById('delete_image');
            if (deleteImageInput) {
                deleteImageInput.value = '1';
            }

            this.clearPreview();
            this.input.value = '';
        }

        handleFormDelete() {
            this.clearPreview();
            this.input.value = '';
        }

        setDefaultAvatar() {
            if (this.previewImg) {
                this.previewImg.src = this.defaultAvatarUrl;
            }
            this.deleteBtn?.classList.add('profile__avatar-delete--hidden');
        }

        clearPreview() {
            if (this.isAvatarField) {
                this.preview?.classList.add('profile__avatar--hidden');
                this.deleteBtn?.classList.add('profile__avatar-delete--hidden');
                if (this.previewImg) {
                    this.previewImg.src = '';
                }
            } else if (this.isLotImageField) {
                this.preview?.classList.remove('form__preview--visible');
                if (this.deleteBtn) {
                    this.deleteBtn.style.display = 'none';
                }
                if (this.previewImg) {
                    this.previewImg.src = '';
                }
            }
        }

        showError(message) {
            if (window.modalNotification) {
                window.modalNotification.error(message);
            } else {
                alert(message);
            }
        }
    }

    const avatarInput = document.querySelector('#avatar');
    const lotInput = document.querySelector('#lot_img');

    if (avatarInput) {
        new ImageUploadHandler('#avatar');
    }

    if (lotInput) {
        new ImageUploadHandler('#lot_img');
    }
});
