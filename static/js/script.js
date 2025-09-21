// static/js/script.js
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const inputs = form.querySelectorAll('input[required], select[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    highlightField(input, false);
                } else {
                    highlightField(input, true);
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showAlert('Please fill in all required fields.', 'error');
            }
        });
    });
    
    // Input field validation on blur
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                highlightField(this, false);
            } else {
                highlightField(this, true);
            }
        });
    });
    
    // Helper function to highlight fields
    function highlightField(field, isValid) {
        if (isValid) {
            field.style.borderColor = '';
            field.style.boxShadow = '';
        } else {
            field.style.borderColor = 'red';
            field.style.boxShadow = '0 0 0 0.2rem rgba(255, 0, 0, 0.25)';
        }
    }
    
    // Helper function to show alerts
    function showAlert(message, type) {
        // Remove any existing alerts
        const existingAlerts = document.querySelectorAll('.custom-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `custom-alert alert-${type}`;
        alertDiv.textContent = message;
        
        // Style the alert
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.padding = '1rem 1.5rem';
        alertDiv.style.borderRadius = '0.25rem';
        alertDiv.style.color = type === 'error' ? '#721c24' : '#155724';
        alertDiv.style.backgroundColor = type === 'error' ? '#f8d7da' : '#d4edda';
        alertDiv.style.border = type === 'error' ? '1px solid #f5c6cb' : '1px solid #c3e6cb';
        alertDiv.style.zIndex = '1000';
        alertDiv.style.maxWidth = '300px';
        alertDiv.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';
        
        // Add to document
        document.body.appendChild(alertDiv);
        
        // Remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
    
    // Add animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);
    });
});





// static/js/script.js
// ... (previous code remains the same) ...

// Add animation to cards
const cards = document.querySelectorAll('.card');
cards.forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    
    setTimeout(() => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 100);
});

// Add chart functionality for state results
function initStateCharts() {
    const chartContainers = document.querySelectorAll('.chart-container');
    
    chartContainers.forEach(container => {
        const chartType = container.dataset.chartType;
        const chartData = JSON.parse(container.dataset.chartData);
        
        if (chartType === 'bar') {
            renderBarChart(container, chartData);
        } else if (chartType === 'pie') {
            renderPieChart(container, chartData);
        }
    });
}

function renderBarChart(container, data) {
    // Simple bar chart implementation
    const maxValue = Math.max(...Object.values(data));
    const chartHeight = 200;
    
    let html = '<div class="bar-chart">';
    
    for (const [party, value] of Object.entries(data)) {
        const barHeight = (value / maxValue) * chartHeight;
        html += `
            <div class="bar" style="height: ${barHeight}px" title="${party}: ${value}">
                <span class="bar-label">${party}</span>
            </div>
        `;
    }
    
    html += '</div>';
    container.innerHTML = html;
}

function renderPieChart(container, data) {
    // Simple pie chart implementation
    const total = Object.values(data).reduce((sum, value) => sum + value, 0);
    let cumulativePercentage = 0;
    
    let html = '<div class="pie-chart">';
    
    for (const [party, value] of Object.entries(data)) {
        const percentage = (value / total) * 100;
        html += `
            <div class="pie-slice" style="--start: ${cumulativePercentage}%; --value: ${percentage}%; --color: ${getRandomColor()}" title="${party}: ${value} (${percentage.toFixed(1)}%)">
                <span class="pie-label">${party}</span>
            </div>
        `;
        cumulativePercentage += percentage;
    }
    
    html += '</div>';
    container.innerHTML = html;
}

function getRandomColor() {
    const colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#6c757d'];
    return colors[Math.floor(Math.random() * colors.length)];
}

// Initialize charts if they exist
if (document.querySelector('.chart-container')) {
    initStateCharts();
}




