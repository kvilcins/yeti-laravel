document.addEventListener('DOMContentLoaded', () => {
    class ImageUploadHandler {
        constructor(inputSelector) {
            this.input = document.querySelector(inputSelector);
            if (!this.input) return;

            this.preview = this.input.closest('.form__item').querySelector('.form__preview, .profile__avatar');
            this.previewImg = this.preview?.querySelector('.form__preview-img, .profile__avatar-img');
            this.deleteBtn = this.preview?.querySelector('.form__delete-btn, .profile__avatar-delete');

            this.isProfileMode = this.input.closest('.profile__form');
            this.defaultAvatarUrl = '/img/default-avatar.jpg';

            this.init();
        }

        init() {
            this.input.addEventListener('change', this.handleFileSelect.bind(this));

            if (this.deleteBtn) {
                this.deleteBtn.addEventListener('click', this.handleDelete.bind(this));
            }
        }

        handleFileSelect(e) {
            const file = e.target.files[0];

            if (!file) {
                this.clearPreview();
                return;
            }

            if (!this.validateFile(file)) {
                e.target.value = '';
                return;
            }

            this.displayPreview(file);
        }

        validateFile(file) {
            const maxSize = this.isProfileMode ? 5 * 1024 * 1024 : 2 * 1024 * 1024;

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

                if (this.isProfileMode) {
                    this.preview?.classList.remove('profile__avatar--hidden');
                    this.deleteBtn?.classList.remove('profile__avatar-delete--hidden');
                } else {
                    this.preview?.classList.add('form__preview--visible');
                    this.createOrShowDeleteBtn();
                }
            };
            reader.readAsDataURL(file);
        }

        createOrShowDeleteBtn() {
            if (!this.deleteBtn) {
                this.deleteBtn = document.createElement('button');
                this.deleteBtn.type = 'button';
                this.deleteBtn.className = 'form__delete-btn';
                this.deleteBtn.innerHTML = '×';
                this.deleteBtn.title = 'Delete image';
                this.deleteBtn.addEventListener('click', this.handleDelete.bind(this));

                this.preview.style.position = 'relative';
                this.preview.appendChild(this.deleteBtn);
            }

            this.deleteBtn.style.display = 'block';
        }

        handleDelete(e) {
            e.preventDefault();

            if (this.isProfileMode) {
                this.handleProfileDelete();
            } else {
                this.handleFormDelete();
            }
        }

        handleProfileDelete() {
            if (window.modalNotification) {
                window.modalNotification.confirm(
                    'Delete Avatar',
                    'Are you sure you want to delete your avatar?',
                    () => {
                        this.setDefaultAvatar();
                        this.input.value = '';
                    }
                );
            } else {
                if (confirm('Are you sure you want to delete your avatar?')) {
                    this.setDefaultAvatar();
                    this.input.value = '';
                }
            }
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
            if (this.isProfileMode) {
                this.preview?.classList.add('profile__avatar--hidden');
                this.deleteBtn?.classList.add('profile__avatar-delete--hidden');
                if (this.previewImg) {
                    this.previewImg.src = '';
                }
            } else {
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

    new ImageUploadHandler('#avatar');
    new ImageUploadHandler('#lot_img');
});
