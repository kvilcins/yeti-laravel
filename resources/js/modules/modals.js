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
    }

    window.modalNotification = new ModalNotification();

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

    handleAvatarPreview();
});
