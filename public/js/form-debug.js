/**
 * Debug script for page creation form
 */
document.addEventListener('DOMContentLoaded', function() {
    const pageForm = document.querySelector('form[action*="pages/store"]');
    
    if (pageForm) {
        console.log('Page creation form found');
        
        // Add form submission debugging
        pageForm.addEventListener('submit', function(e) {
            console.log('Form submission attempted');
            
            const formData = new FormData(this);
            let isValid = true;
            let errorFields = [];
            
            // Check required fields
            formData.forEach((value, key) => {
                console.log(`Field ${key}: ${value}`);
                const field = document.querySelector(`[name="${key}"]`);
                if (field && field.required && !value) {
                    isValid = false;
                    errorFields.push(key);
                }
            });
            
            if (!isValid) {
                console.error('Form validation failed:', errorFields);
            } else {
                console.log('Form appears valid');
            }
        });
        
        // Add CSRF token validation
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token meta tag not found');
            
            // Add CSRF token meta tag if missing
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = document.querySelector('input[name="_token"]').value;
            document.head.appendChild(meta);
            console.log('Added missing CSRF token meta tag');
        }
    }
});
