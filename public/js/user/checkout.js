class CheckoutHandler {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCart();
        this.loadAddresses();
        this.loadDeliveryAreas();
    }

    bindEvents() {
        const form = document.getElementById('checkoutForm');
        if (form) {
            form.addEventListener('submit', (e) => this.handleSubmit(e));
        }

        const addressSelect = document.getElementById('address_id');
        if (addressSelect) {
            addressSelect.addEventListener('change', () => this.onAddressChange());
        }

        const applyCouponBtn = document.getElementById('applyCouponBtn');
        if (applyCouponBtn) {
            applyCouponBtn.addEventListener('click', () => this.applyCoupon());
        }
    }

    async loadAddresses() {
        try {
            const response = await fetch('/addresses', {
                headers: { 'Accept': 'application/json' }
            });
            const addresses = await response.json();
            this.renderAddresses(addresses);
        } catch (error) {
            console.error('Error loading addresses:', error);
        }
    }

    renderAddresses(addresses) {
        const select = document.getElementById('address_id');
        if (!select) return;

        select.innerHTML = '<option value="">Choose an address</option>';
        
        (addresses || []).forEach(addr => {
            select.innerHTML += `<option value="${addr.id}">${addr.label} - ${addr.street}, ${addr.barangay}</option>`;
        });
    }

    async loadDeliveryAreas() {
        try {
            const response = await fetch('/delivery-areas', {
                headers: { 'Accept': 'application/json' }
            });
            const areas = await response.json();
            this.renderDeliveryAreas(areas);
        } catch (error) {
            console.error('Error loading delivery areas:', error);
        }
    }

    renderDeliveryAreas(areas) {
        const select = document.getElementById('delivery_area');
        if (!select) return;

        select.innerHTML = '<option value="">Select area</option>';
        
        (areas || []).forEach(area => {
            select.innerHTML += `<option value="${area.id}" data-fee="${area.fee}">${area.name} - ₱${area.fee}</option>`;
        });

        select.addEventListener('change', () => this.updateDeliveryFee());
    }

    updateDeliveryFee() {
        const select = document.getElementById('delivery_area');
        const option = select?.selectedOptions[0];
        const fee = option?.dataset.fee || 0;
        document.getElementById('summaryDelivery').textContent = `₱${fee}`;
        this.updateTotal();
    }

    async loadCart() {
        try {
            const response = await fetch('/cart', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();
            this.renderSummary(data);
        } catch (error) {
            console.error('Error loading cart:', error);
        }
    }

    renderSummary(data) {
        const container = document.getElementById('summaryItems');
        const subtotal = data.subtotal || 0;
        
        if (container) {
            container.innerHTML = (data.items || []).map(item => `
                <div class="summary-item">
                    <span>${item.quantity}x ${item.product?.name || 'Product'}</span>
                    <span>₱${item.subtotal}</span>
                </div>
            `).join('');
        }

        document.getElementById('summarySubtotal').textContent = `₱${subtotal}`;
        document.getElementById('summaryTotal').textContent = `₱${subtotal}`;
    }

    updateTotal() {
        const subtotal = parseFloat(document.getElementById('summarySubtotal').textContent.replace('₱', '')) || 0;
        const delivery = parseFloat(document.getElementById('summaryDelivery').textContent.replace('₱', '')) || 0;
        const discount = parseFloat(document.getElementById('summaryDiscount').textContent.replace('-₱', '')) || 0;
        const total = subtotal + delivery - discount;
        document.getElementById('summaryTotal').textContent = `₱${total}`;
    }

    onAddressChange() {
    }

    async applyCoupon() {
        const code = document.getElementById('coupon_code').value;
        if (!code) return;

        try {
            const response = await fetch('/coupons/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({ code })
            });
            const result = await response.json();

            const msgDiv = document.getElementById('couponMessage');
            if (response.ok) {
                msgDiv.innerHTML = `<div class="alert alert-success">Coupon applied! -₱${result.discount}</div>`;
                document.getElementById('summaryDiscount').textContent = `-₱${result.discount}`;
                this.updateTotal();
            } else {
                msgDiv.innerHTML = `<div class="alert alert-error">${result.message}</div>`;
            }
        } catch (error) {
            console.error('Error applying coupon:', error);
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        const form = e.target;
        const submitBtn = form.querySelector('.btn-submit');

        this.setLoading(submitBtn, true);

        try {
            const formData = new FormData(form);
            const data = {
                name: formData.get('name'),
                phone: formData.get('phone'),
                address_id: formData.get('address_id'),
                delivery_area: formData.get('delivery_area'),
                delivery_date: formData.get('delivery_date'),
                delivery_time: formData.get('delivery_time'),
                delivery_notes: formData.get('delivery_notes'),
                gift_message: formData.get('gift_message'),
                payment_method: formData.get('payment_method'),
                coupon_code: formData.get('coupon_code')
            };

            const response = await fetch('/checkout/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (!response.ok) {
                if (result.errors) this.showValidationErrors(result.errors);
                else this.showAlert(result.message || 'Order failed', 'error');
                return;
            }

            this.showAlert('Order placed successfully!', 'success');
            setTimeout(() => window.location.href = `/orders/${result.order_id}`, 1500);

        } catch (error) {
            this.showAlert('An error occurred.', 'error');
        } finally {
            this.setLoading(submitBtn, false);
        }
    }

    setLoading(btn, isLoading) {
        if (isLoading) {
            btn.classList.add('btn-loading');
            btn.disabled = true;
        } else {
            btn.classList.remove('btn-loading');
            btn.disabled = false;
        }
    }

    showValidationErrors(errors) {
        for (const [field, messages] of Object.entries(errors)) {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('error');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'form-error';
                errorDiv.textContent = messages[0];
                input.parentNode.appendChild(errorDiv);
            }
        }
    }

    showAlert(message, type) {
        const container = document.getElementById('alertContainer');
        if (!container) return;
        container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => container.innerHTML = '', 5000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new CheckoutHandler();
});