class AddressesHandler {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadAddresses();
    }

    bindEvents() {
        const addBtn = document.getElementById('addAddressBtn');
        const closeBtn = document.getElementById('closeModal');
        const modal = document.getElementById('addressModal');
        const form = document.getElementById('addressForm');

        if (addBtn) {
            addBtn.addEventListener('click', () => this.openModal());
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.closeModal());
        }

        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) this.closeModal();
            });
        }

        if (form) {
            form.addEventListener('submit', (e) => this.handleSubmit(e));
        }
    }

    async loadAddresses() {
        try {
            const response = await fetch('/profile/addresses', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) return;

            const addresses = await response.json();
            this.renderAddresses(addresses);
        } catch (error) {
            console.error('Error loading addresses:', error);
        }
    }

    renderAddresses(addresses) {
        const list = document.getElementById('addressesList');
        const empty = document.getElementById('emptyState');

        if (!list) return;

        if (!addresses || addresses.length === 0) {
            list.innerHTML = '';
            if (empty) empty.style.display = 'block';
            return;
        }

        if (empty) empty.style.display = 'none';

        list.innerHTML = addresses.map(addr => `
            <div class="address-card ${addr.is_default ? 'default' : ''}" data-id="${addr.id}">
                <div class="address-label">${this.escapeHtml(addr.label)}</div>
                <div class="address-details">
                    <div>${this.escapeHtml(addr.recipient_name)}</div>
                    <div>${this.escapeHtml(addr.phone)}</div>
                    <div>${this.escapeHtml(addr.street)}, ${this.escapeHtml(addr.barangay)}</div>
                    <div>${this.escapeHtml(addr.city)}, ${this.escapeHtml(addr.province)} ${this.escapeHtml(addr.postal_code)}</div>
                </div>
                <div class="address-actions">
                    <button class="btn btn-primary btn-edit" data-id="${addr.id}">Edit</button>
                    <button class="btn btn-remove btn-delete" data-id="${addr.id}">Delete</button>
                </div>
            </div>
        `).join('');

        list.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', (e) => this.editAddress(e.target.dataset.id));
        });

        list.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => this.deleteAddress(e.target.dataset.id));
        });
    }

    openModal(address = null) {
        const modal = document.getElementById('addressModal');
        const form = document.getElementById('addressForm');
        const title = document.getElementById('modalTitle');

        if (!modal || !form) return;

        form.reset();
        document.getElementById('addressId').value = '';

        if (address) {
            title.textContent = 'Edit Address';
            document.getElementById('addressId').value = address.id;
            document.getElementById('label').value = address.label || '';
            document.getElementById('recipient_name').value = address.recipient_name || '';
            document.getElementById('phone').value = address.phone || '';
            document.getElementById('street').value = address.street || '';
            document.getElementById('barangay').value = address.barangay || '';
            document.getElementById('city').value = address.city || '';
            document.getElementById('province').value = address.province || '';
            document.getElementById('postal_code').value = address.postal_code || '';
            document.getElementById('is_default').checked = address.is_default || false;
        } else {
            title.textContent = 'Add Address';
        }

        modal.classList.add('active');
    }

    closeModal() {
        const modal = document.getElementById('addressModal');
        if (modal) {
            modal.classList.remove('active');
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        const form = e.target;
        const submitBtn = form.querySelector('.btn-submit');
        const addressId = document.getElementById('addressId').value;

        const formData = new FormData(form);
        const data = {
            label: formData.get('label'),
            recipient_name: formData.get('recipient_name'),
            phone: formData.get('phone'),
            street: formData.get('street'),
            barangay: formData.get('barangay'),
            city: formData.get('city'),
            province: formData.get('province'),
            postal_code: formData.get('postal_code'),
            is_default: formData.get('is_default') === 'on'
        };

        this.setLoading(submitBtn, true);

        try {
            const url = addressId ? `/profile/addresses/${addressId}` : '/profile/addresses';
            const method = addressId ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (!response.ok) {
                if (result.errors) {
                    this.showValidationErrors(result.errors);
                } else {
                    this.showAlert(result.message || 'Save failed', 'error');
                }
                return;
            }

            this.showAlert('Address saved successfully!', 'success');
            this.closeModal();
            this.loadAddresses();

        } catch (error) {
            this.showAlert('An error occurred. Please try again.', 'error');
            console.error('Address save error:', error);
        } finally {
            this.setLoading(submitBtn, false);
        }
    }

    async editAddress(id) {
        try {
            const response = await fetch(`/profile/addresses/${id}`, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) return;

            const address = await response.json();
            this.openModal(address);
        } catch (error) {
            console.error('Error loading address:', error);
        }
    }

    async deleteAddress(id) {
        if (!confirm('Are you sure you want to delete this address?')) return;

        try {
            const response = await fetch(`/profile/addresses/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (!response.ok) {
                this.showAlert(result.message || 'Delete failed', 'error');
                return;
            }

            this.showAlert('Address deleted successfully!', 'success');
            this.loadAddresses();

        } catch (error) {
            this.showAlert('An error occurred. Please try again.', 'error');
            console.error('Delete error:', error);
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
        const alertContainer = document.getElementById('alertContainer');
        if (!alertContainer) return;

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;

        alertContainer.innerHTML = '';
        alertContainer.appendChild(alertDiv);

        setTimeout(() => alertDiv.remove(), 5000);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new AddressesHandler();
});