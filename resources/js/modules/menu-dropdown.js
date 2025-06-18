document.addEventListener('DOMContentLoaded', () => {
    const userImage = document.querySelector('.user-menu__avatar');
    const dropdown = document.querySelector('.user-menu__dropdown');

    userImage.addEventListener('click', e => {
        dropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', e => {
        if (!userImage.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});
