document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.profile__nav-btn');
    const tabPanels = document.querySelectorAll('.profile__panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            tabButtons.forEach(btn => btn.classList.remove('profile__nav-btn--active'));
            tabPanels.forEach(panel => panel.classList.remove('profile__panel--active'));

            this.classList.add('profile__nav-btn--active');

            const targetPanel = document.getElementById(targetTab + '-tab');
            if (targetPanel) {
                targetPanel.classList.add('profile__panel--active');
            }
        });
    });

    const passwordInput = document.getElementById('password');
    const currentPasswordGroup = document.getElementById('currentPasswordGroup');
    const confirmPasswordGroup = document.getElementById('password_confirmationGroup');

    if (passwordInput && currentPasswordGroup && confirmPasswordGroup) {
        passwordInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                currentPasswordGroup.classList.remove('profile__field--hidden');
                confirmPasswordGroup.classList.remove('profile__field--hidden');
            } else {
                currentPasswordGroup.classList.add('profile__field--hidden');
                confirmPasswordGroup.classList.add('profile__field--hidden');

                document.getElementById('current_password').value = '';
                document.getElementById('password_confirmation').value = '';
            }
        });
    }
});
