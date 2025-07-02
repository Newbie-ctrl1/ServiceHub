/**
 * Main JavaScript file for handling responsive functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi menu hamburger
    initHamburgerMenu();
    
    // Inisialisasi responsive behavior
    initResponsiveBehavior();
});

/**
 * Initialize hamburger menu functionality
 */
function initHamburgerMenu() {
    const menuToggle = document.getElementById('menu-toggle');
    const menuOverlay = document.getElementById('menu-overlay');
    const sidebar = document.getElementById('sidebar');
    const body = document.body;
    
    if (menuToggle && menuOverlay && sidebar) {
        // Toggle menu when hamburger icon is clicked
        menuToggle.addEventListener('click', function() {
            menuToggle.classList.toggle('ham-style');
            menuOverlay.classList.toggle('active');
            sidebar.classList.toggle('left');
            body.classList.toggle('menu-open');
        });
        
        // Close menu when overlay is clicked
        menuOverlay.addEventListener('click', function() {
            menuToggle.classList.remove('ham-style');
            menuOverlay.classList.remove('active');
            sidebar.classList.remove('left');
            body.classList.remove('menu-open');
        });
    }
}

/**
 * Initialize responsive behavior
 */
function initResponsiveBehavior() {
    // Handle window resize events
    window.addEventListener('resize', function() {
        const menuToggle = document.getElementById('menu-toggle');
        const menuOverlay = document.getElementById('menu-overlay');
        const sidebar = document.getElementById('sidebar');
        const body = document.body;
        
        // If window width is greater than 768px (tablet/desktop), close mobile menu
        if (window.innerWidth > 768 && menuToggle && menuOverlay && sidebar) {
            menuToggle.classList.remove('ham-style');
            menuOverlay.classList.remove('active');
            sidebar.classList.remove('left');
            body.classList.remove('menu-open');
        }
    });
    
    // Improve touch targets for mobile
    improveTouchTargets();
}

/**
 * Improve touch targets for mobile devices
 */
function improveTouchTargets() {
    // Add touch-friendly classes to interactive elements
    const touchElements = document.querySelectorAll('a, button, input, select, textarea');
    
    touchElements.forEach(function(element) {
        element.classList.add('touch-friendly');
    });
}

/**
 * Initialize sidebar functionality
 */
function initSidebar() {
    // Add any jQuery-specific sidebar functionality here
    if (typeof $ !== 'undefined') {
        // Example: Close sidebar when menu item is clicked on mobile
        $('.menu-links .nav-item a').on('click', function() {
            if (window.innerWidth <= 768) {
                const menuToggle = document.getElementById('menu-toggle');
                const menuOverlay = document.getElementById('menu-overlay');
                const sidebar = document.getElementById('sidebar');
                const body = document.body;
                
                if (menuToggle && menuOverlay && sidebar) {
                    menuToggle.classList.remove('ham-style');
                    menuOverlay.classList.remove('active');
                    sidebar.classList.remove('left');
                    body.classList.remove('menu-open');
                }
            }
        });
    }
}