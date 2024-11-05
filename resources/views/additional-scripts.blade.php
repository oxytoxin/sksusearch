<script>
    const sidebarNav = document.querySelector('.filament-sidebar-nav');
    const SCROLL_POSITION_KEY = 'SIDEBAR_SCROLL_TOP';

    window.addEventListener('DOMContentLoaded', () => {
        sidebarNav.scrollBy(0, Number(window.localStorage.getItem(SCROLL_POSITION_KEY) || 0))
        Array.from(document.querySelectorAll('.filament-sidebar-item')).forEach(sidebarItem => {
            sidebarItem.addEventListener('click', e => {
                const scrollTop = sidebarNav.scrollTop;
                window.localStorage.setItem(SCROLL_POSITION_KEY, scrollTop);
            });
        });
    });
</script>
