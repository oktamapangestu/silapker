function initMobileMenu() {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('mobile-menu-icon-open');
    const iconClose = document.getElementById('mobile-menu-icon-close');

    if (! toggle || ! menu) {
        return;
    }

    toggle.addEventListener('click', () => {
        const isHidden = menu.classList.toggle('hidden');

        toggle.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
        iconOpen.classList.toggle('hidden', ! isHidden);
        iconClose.classList.toggle('hidden', isHidden);
    });
}

document.addEventListener('DOMContentLoaded', initMobileMenu);
