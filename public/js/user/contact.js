class ContactHandler {
    /**
     * Constructor - Initialize the handler
     */
    constructor() {
        // Get CSRF token from meta tag
        // document.querySelector() finds HTML element
        // ?.getAttribute() safely gets attribute (null if not found)
        // || '' provides default empty string if token missing
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        // Initialize the form
        this.init();
    }

    /**
     * init() - Set up event listeners
     */
    init() {
        // Find the contact form
        const form = document.getElementById('contactForm');
        
        // If form exists, add submit listener
        if (form) {
            // Listen for form submission
            form.addEventListener('submit', (e) => this.handleSubmit(e));
        }
    }

    /**
     * handleSubmit() - Process form submission
     * @param e - Event object
     */
    async handleSubmit(e) {
        // Prevent default form submission (page reload)
        e.preventDefault();

        // Get the form element
        const form = e.target;
        
        // Find submit button
        const submitBtn = form.querySelector('.btn-submit');

        // Show loading state
        this.setLoading(submitBtn, true);

        try {
            // Get form data using FormData API
            // FormData automatically grabs all name/value pairs from form
            const formData = new FormData(form);
            
            // Create data object from form fields
            // formData.get() gets value by field name
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                phone: formData.get('phone'),     // Optional field
                message: formData.get('message')
            };

            // Send POST request to /contact
            const response = await fetch('/contact', {
                method: 'POST',                          // HTTP method for creating data
                headers: {
                    'Content-Type': 'application/json', // We're sending JSON
                    'X-CSRF-TOKEN': this.csrfToken,      // Security token
                    'Accept': 'application/json'       // We expect JSON back
                },
                // Convert data object to JSON string
                body: JSON.stringify(data)
            });

            // Parse JSON response
            const result = await response.json();

            // Check if request failed
            if (!response.ok) {
                // Show error message
                this.showAlert(result.message || 'Failed to send', 'error');
                return;
            }

            // Success! Show message and reset form
            this.showAlert('Message sent successfully!', 'success');
            form.reset();

        } catch (error) {
            // Network error or other issue
            console.error('Contact form error:', error);
            this.showAlert('An error occurred. Please try again.', 'error');
            
        } finally {
            // Always run this - remove loading state
            this.setLoading(submitBtn, false);
        }
    }

    /**
     * setLoading() - Toggle button loading state
     * @param btn - Button element
     * @param isLoading - Boolean for loading state
     */
    setLoading(btn, isLoading) {
        if (isLoading) {
            // Add loading class and disable button
            btn.classList.add('btn-loading');
            btn.disabled = true;
        } else {
            // Remove loading class and enable button
            btn.classList.remove('btn-loading');
            btn.disabled = false;
        }
    }

    /**
     * showAlert() - Display alert message
     * @param message - Text to show
     * @param type - 'success' or 'error'
     */
    showAlert(message, type) {
        // Find alert container
        const container = document.getElementById('alertContainer');
        if (!container) return;  // Exit if no container
        
        // Create alert HTML
        container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        
        // Remove after 5 seconds
        setTimeout(() => container.innerHTML = '', 5000);
    }
}

// Run when page loads
document.addEventListener('DOMContentLoaded', () => {
    // Create new ContactHandler instance
    new ContactHandler();
});

/**
 * ==============================================================================
 * KEY JAVASCRIPT CONCEPTS EXPLAINED
 * ==============================================================================
 * 
 * class ClassName {
 *     // Blueprint for creating objects
 *     // Contains methods (functions) and properties (variables)
 * }
 * 
 * constructor() {
 *     // Special method that runs when we create new ClassName()
 *     // Used to set up initial values
 * }
 * 
 * document.querySelector('selector')       - Find first matching element
 * document.querySelectorAll('selector') - Find all matching elements
 * 
 * element.addEventListener('event', callback) - Listen for events
 * element.classList.add/remove('class')  - Add/remove CSS classes
 * 
 * async function name() {
 *     // Function that can use 'await'
 * }
 * 
 * await fetch(url, options) - Make HTTP request
 * JSON.stringify(obj)     - Convert to JSON string
 * JSON.parse(string)       - Convert from JSON string
 * 
 * formData.get('name')      - Get form field value
 * formData.has('name')     - Check if field exists
 * 
 * try/catch/finally        - Error handling
 */