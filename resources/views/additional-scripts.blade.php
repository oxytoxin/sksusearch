<script>
    (() => {
        const scrollPositionKey = 'SIDEBAR_SCROLL_TOP';

        const restoreSidebarScroll = () => {
            const sidebarNav = document.querySelector('.fi-sidebar-nav');

            if (! sidebarNav) {
                return;
            }

            sidebarNav.scrollTop = Number(window.localStorage.getItem(scrollPositionKey) || 0);

            sidebarNav.addEventListener('click', (event) => {
                if (! event.target.closest('.fi-sidebar-item')) {
                    return;
                }

                window.localStorage.setItem(scrollPositionKey, sidebarNav.scrollTop);
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', restoreSidebarScroll, { once: true });
        } else {
            restoreSidebarScroll();
        }

        document.addEventListener('livewire:navigated', restoreSidebarScroll);
    })();
</script>
