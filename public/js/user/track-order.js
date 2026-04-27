class TrackOrderHandler {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        const form = document.getElementById('trackOrderForm');
        if (form) {
            form.addEventListener('submit', (e) => this.handleSubmit(e));
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
                order_id: formData.get('order_id'),
                email: formData.get('email')
            };

            const response = await fetch('/track-order', {
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
                this.showAlert(result.message || 'Order not found', 'error');
                return;
            }

            this.renderTracking(result);

        } catch (error) {
            this.showAlert('An error occurred.', 'error');
        } finally {
            this.setLoading(submitBtn, false);
        }
    }

    renderTracking(order) {
        const result = document.getElementById('trackingResult');
        const timeline = document.getElementById('orderTimeline');
        const info = document.getElementById('orderInfo');

        if (!result) return;

        result.style.display = 'block';
        
        const statuses = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];
        const currentIndex = statuses.indexOf(order.status);

        if (timeline) {
            timeline.innerHTML = `
                <div class="timeline">
                    ${statuses.map((s, i) => `
                        <div class="timeline-step ${i <= currentIndex ? 'active' : ''} ${i === currentIndex ? 'current' : ''}">
                            <div class="step-dot"></div>
                            <div class="step-label">${s.replace('_', ' ')}</div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        if (info) {
            info.innerHTML = `
                <div class="order-info-card">
                    <h3>Order #${order.order_number}</h3>
                    <p><strong>Status:</strong> ${order.status}</p>
                    <p><strong>Total:</strong> ₱${order.total}</p>
                    <p><strong>Delivery Date:</strong> ${order.delivery_date}</p>
                    ${order.tracking_number ? `<p><strong>Tracking:</strong> ${order.tracking_number}</p>` : ''}
                </div>
            `;
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

    showAlert(message, type) {
        const container = document.getElementById('alertContainer');
        if (!container) return;
        container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => container.innerHTML = '', 5000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new TrackOrderHandler();
});