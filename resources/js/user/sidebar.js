/**
 * Sidebar Toggle Script
 * Handles mobile sidebar menu toggle functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('mobileMenuToggle');

    if (!sidebar || !overlay || !toggleBtn) {
        return; // Exit if elements not found
    }

    /**
     * Toggle sidebar visibility
     */
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
        
        // Toggle icon
        const icon = toggleBtn.querySelector('i');
        if (icon) {
            if (sidebar.classList.contains('show')) {
                icon.classList.remove('bi-list');
                icon.classList.add('bi-x');
            } else {
                icon.classList.remove('bi-x');
                icon.classList.add('bi-list');
            }
        }
    });

    /**
     * Close sidebar when clicking overlay
     */
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        
        // Reset icon
        const icon = toggleBtn.querySelector('i');
        if (icon) {
            icon.classList.remove('bi-x');
            icon.classList.add('bi-list');
        }
    });

    /**
     * Close sidebar when clicking nav link (mobile only)
     */
    const navLinks = sidebar.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                
                // Reset icon
                const icon = toggleBtn.querySelector('i');
                if (icon) {
                    icon.classList.remove('bi-x');
                    icon.classList.add('bi-list');
                }
            }
        });
    });

    /**
     * Close sidebar on ESC key press
     */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            
            // Reset icon
            const icon = toggleBtn.querySelector('i');
            if (icon) {
                icon.classList.remove('bi-x');
                icon.classList.add('bi-list');
            }
        }
    });

    /**
     * Handle window resize
     * Hide sidebar when switching to mobile view
     */
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 768 && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                
                // Reset icon
                const icon = toggleBtn.querySelector('i');
                if (icon) {
                    icon.classList.remove('bi-x');
                    icon.classList.add('bi-list');
                }
            }
        }, 250);
    });
});
