/**
 * Conditional Display Handler
 * Handles show/hide functionality for elements with conditional-display class
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeConditionalDisplay();
});

function initializeConditionalDisplay() {
    // Find all elements with conditional-display class
    const conditionalElements = document.querySelectorAll('.conditional-display');
    
    conditionalElements.forEach(element => {
        const condition = element.dataset.condition;
        const fieldName = element.dataset.field;
        const currentValue = element.dataset.current;
        
        if (condition && fieldName) {
            // Set initial visibility
            updateElementVisibility(element, condition, currentValue);
            
            // Find the controlling field
            const controllingField = document.querySelector(`[name="${fieldName}"]`);
            
            if (controllingField) {
                // Add event listener for changes
                controllingField.addEventListener('change', function() {
                    updateElementVisibility(element, condition, this.value);
                });
                
                // Also listen for input events for real-time updates
                controllingField.addEventListener('input', function() {
                    updateElementVisibility(element, condition, this.value);
                });
            }
        }
    });
}

function updateElementVisibility(element, condition, currentValue) {
    // Parse conditions (can be comma-separated for multiple values)
    const conditions = condition.split(',').map(c => c.trim());
    
    // Check if current value matches any condition
    const shouldShow = conditions.includes(currentValue);
    
    // Update visibility with smooth transition
    if (shouldShow) {
        element.style.display = 'block';
        element.style.opacity = '0';
        element.style.transform = 'translateY(-10px)';
        
        // Animate in
        setTimeout(() => {
            element.style.transition = 'all 0.3s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 10);
    } else {
        element.style.transition = 'all 0.3s ease';
        element.style.opacity = '0';
        element.style.transform = 'translateY(-10px)';
        
        // Hide after animation
        setTimeout(() => {
            element.style.display = 'none';
        }, 300);
    }
}

// Re-initialize when new content is loaded dynamically
function reinitializeConditionalDisplay() {
    initializeConditionalDisplay();
}

// Export for use in other scripts
window.conditionalDisplay = {
    initialize: initializeConditionalDisplay,
    reinitialize: reinitializeConditionalDisplay,
    updateVisibility: updateElementVisibility
};