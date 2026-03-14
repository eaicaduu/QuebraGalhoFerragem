document.addEventListener('DOMContentLoaded', () => {
    const collapseMobile = document.getElementById('menuMobile');
    const icon = document.getElementById('iconMenuMobile');

    collapseMobile.addEventListener('show.bs.collapse', () => {
        icon.classList.add('open');
    });

    collapseMobile.addEventListener('hide.bs.collapse', () => {
        icon.classList.remove('open');
    });
});