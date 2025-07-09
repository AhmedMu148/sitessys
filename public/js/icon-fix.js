/**
 * Fix for Feather Icons in Admin Panel
 */
document.addEventListener('DOMContentLoaded', function() {
    // Check if feather is loaded
    if (typeof feather !== 'undefined') {
        // Initialize feather icons
        feather.replace();
        console.log('Feather icons initialized');
    } else {
        console.error('Feather icons not loaded');
        
        // Try loading Feather icons if not available
        var script = document.createElement('script');
        script.src = 'https://unpkg.com/feather-icons';
        script.onload = function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
                console.log('Feather icons loaded and initialized');
            }
        };
        document.head.appendChild(script);
    }
});
