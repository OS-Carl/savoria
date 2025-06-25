document.addEventListener('DOMContentLoaded', function() {
    // Menu Tabs
    const menuTabs = document.querySelectorAll('.menu-tab');
    const menuItems = document.querySelectorAll('.menu-items');
    
    menuTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs
            menuTabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            tab.classList.add('active');
            
            // Hide all menu items
            menuItems.forEach(item => item.style.display = 'none');
            
            // Show the selected menu items
            const category = tab.getAttribute('data-category');
            document.getElementById(category).style.display = 'grid';
        });
    });
    
    // Mobile Menu Toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const nav = document.querySelector('nav ul');
    
    mobileMenuBtn.addEventListener('click', () => {
        nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
    });
    
    // Order Form Calculation
    const orderForm = document.getElementById('order-form');
    const orderItemInputs = document.querySelectorAll('.order-item input');
    const orderItemsSummary = document.getElementById('order-items-summary');
    const orderTotal = document.getElementById('order-total');
    
    // Menu prices
    const prices = {
        'bruschetta': 8.99,
        'rabas': 12.99,
        'dip_de_espinacas_y_alcachofas': 10.99,
        'salmon': 24.99,
        'bife_de_chorizo': 32.99,
        'risotto': 18.99,
        'tiramisu': 8.99,
        'volcan_de_chocolate': 9.99,
        'budin': 7.99,
        'cocteles': 12.99,
        'cerveza': 7.99,
        'vino': 9.99
    };
    
    // Menu item names
    const itemNames = {
        'bruschetta': 'Bruschetta',
        'rabas': 'Rabas',
        'dip_de_espinacas_y_alcachofas': 'Dip de Espinacas y Alcachofas',
        'salmon': 'Salmon grillado',
        'bife_de_chorizo': 'Bife de chorizo',
        'risotto': 'Risotto',
        'tiramisu': 'Tiramisu',
        'volcan_de_chocolate': 'Volcan de chocolate',
        'budin': 'Budin',
        'cocteles': 'Cocteles/Tragos',
        'cerveza': 'Cerveza artesanal',
        'vino': 'Vino'
    };
    
    // Update order summary when quantity changes
    orderItemInputs.forEach(input => {
        input.addEventListener('change', updateOrderSummary);
    });
    
    function updateOrderSummary() {
        let total = 0;
        let summaryHTML = '';
        let hasItems = false;
        
        orderItemInputs.forEach(input => {
            const itemId = input.id;
            const quantity = parseInt(input.value);
            
            if (quantity > 0) {
                hasItems = true;
                const price = prices[itemId];
                const itemTotal = price * quantity;
                total += itemTotal;
                
                summaryHTML += `
                    <div class="summary-item">
                        <span>${itemNames[itemId]} x ${quantity}</span>
                        <span>$${itemTotal.toFixed(2)}</span>
                    </div>
                `;
            }
        });
        
        if (hasItems) {
            orderItemsSummary.innerHTML = summaryHTML;
        } else {
            orderItemsSummary.innerHTML = '<p>No items selected</p>';
        }
        
        orderTotal.textContent = `$${total.toFixed(2)}`;
    }
    
    // Form Validation
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            let total = 0;
            let hasItems = false;
            
            orderItemInputs.forEach(input => {
                const quantity = parseInt(input.value);
                if (quantity > 0) {
                    hasItems = true;
                }
            });
            
            if (!hasItems) {
                e.preventDefault();
                alert('Please select at least one item to order.');
            }
        });
    }
    
    // Reservation date validation
    const resDateInput = document.getElementById('res_date');
    if (resDateInput) {
        // Set min date to today
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const formattedToday = `${yyyy}-${mm}-${dd}`;
        
        resDateInput.setAttribute('min', formattedToday);
        
        // Validate date is not in the past
        resDateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                alert('Please select a future date.');
                this.value = '';
            }
        });
    }
});