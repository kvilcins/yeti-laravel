document.addEventListener('DOMContentLoaded', function() {
    const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');

    if (deleteAvatarBtn) {
        deleteAvatarBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (confirm('Are you sure you want to delete your avatar?')) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                fetch('/account/avatar', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('avatarPreview').src = data.avatar_url;
                            deleteAvatarBtn.style.display = 'none';
                            window.modalNotification?.success(data.message) || alert(data.message);
                        } else {
                            window.modalNotification?.error(data.message) || alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.modalNotification?.error('Error deleting avatar') || alert('Error deleting avatar');
                    });
            }
        });
    }
});
