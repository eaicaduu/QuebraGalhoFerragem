document.addEventListener('DOMContentLoaded', () => {
    const collapseMobile = document.getElementById('menuMobile');
    const iconMenuMobile = document.getElementById('iconMenuMobile');

    const submenuTriggers = document.querySelectorAll(
        '[data-bs-toggle="collapse"][href^="#desktop_menu_"], [data-bs-toggle="collapse"][href^="#mobile_menu_"]'
    );

    submenuTriggers.forEach((trigger) => {
        const targetSelector = trigger.getAttribute('href');
        if (!targetSelector) return;

        const parentItem = trigger.closest('.nav-item');
        if (!parentItem) return;

        const collapseEl = parentItem.querySelector(targetSelector);
        if (!collapseEl) return;

        const iconSubmenu = trigger.querySelector('.submenu-arrow');
        if (!iconSubmenu) return;

        collapseEl.addEventListener('show.bs.collapse', () => {
            iconSubmenu.classList.add('rotated');
        });

        collapseEl.addEventListener('hide.bs.collapse', () => {
            iconSubmenu.classList.remove('rotated');
        });
    });

    if (collapseMobile && iconMenuMobile) {
        collapseMobile.addEventListener('show.bs.collapse', (event) => {
            if (event.target !== collapseMobile) return;
            iconMenuMobile.classList.add('open');
        });

        collapseMobile.addEventListener('hide.bs.collapse', (event) => {
            if (event.target !== collapseMobile) return;
            iconMenuMobile.classList.remove('open');
        });
    }
});