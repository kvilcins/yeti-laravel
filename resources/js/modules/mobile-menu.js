document.addEventListener('DOMContentLoaded', function () {
    const burger = document.getElementById('burger-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const closeBtn = document.getElementById('mobile-menu-close');

    if (burger && mobileMenu) {
        burger.addEventListener('click', function () {
            mobileMenu.classList.toggle('open');
            mobileMenu.classList.toggle('hidden');
            document.body.classList.toggle('menu-open');
        });
    }

    if (closeBtn && mobileMenu) {
        closeBtn.addEventListener('click', function () {
            mobileMenu.classList.remove('open');
            mobileMenu.classList.add('hidden');
            document.body.classList.remove('menu-open');
        });
    }
});
