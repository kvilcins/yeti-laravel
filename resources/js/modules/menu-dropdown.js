document.addEventListener('DOMContentLoaded', function () {
    const userImage = document.querySelector('.user-menu__avatar');
    const dropdown = document.querySelector('.user-menu__dropdown');

    userImage.addEventListener('click', function (e) {
        dropdown.classList.toggle('hidden');
    });

    // Скрыть меню при клике вне его
    document.addEventListener('click', function (e) {
        if (!userImage.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});
