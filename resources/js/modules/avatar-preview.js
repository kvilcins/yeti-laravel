document.addEventListener('DOMContentLoaded', () => {
    const avatarInput = document.getElementById('avatar');
    const avatarContainer = document.getElementById('avatarContainer');
    const avatarPreview = document.getElementById('avatarPreview');
    const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');

    if (avatarInput) {
        avatarInput.addEventListener('change', (e) => {
            const file = e.target.files[0];

            if (!file) {
                avatarContainer.classList.add('profile__avatar--hidden');
                deleteAvatarBtn.classList.add('profile__avatar-delete--hidden');
                avatarPreview.src = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                if (window.modalNotification) {
                    window.modalNotification.error('File size must be less than 2MB');
                } else {
                    alert('File size must be less than 2MB');
                }
                e.target.value = '';
                return;
            }

            if (!file.type.startsWith('image/')) {
                if (window.modalNotification) {
                    window.modalNotification.error('Please select a valid image file');
                } else {
                    alert('Please select a valid image file');
                }
                e.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                avatarPreview.src = event.target.result;
                avatarContainer.classList.remove('profile__avatar--hidden');
                deleteAvatarBtn.classList.remove('profile__avatar-delete--hidden');
            };
            reader.readAsDataURL(file);
        });

        if (deleteAvatarBtn) {
            deleteAvatarBtn.addEventListener('click', (e) => {
                e.preventDefault();
                avatarInput.value = '';
                avatarContainer.classList.add('profile__avatar--hidden');
                deleteAvatarBtn.classList.add('profile__avatar-delete--hidden');
                avatarPreview.src = '';
            });
        }
    }
});
