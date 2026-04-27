class ProductsHandler {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.modal = document.getElementById('productModal');
        this.form = document.getElementById('productForm');
        this.editingId = null;
        this.init();
    }

    init() {
        this.loadProducts();
        this.loadCategories();
        this.bindEvents();
    }

    bindEvents() {
        document.getElementById('searchInput')?.addEventListener('input', () => this.loadProducts());
        document.getElementById('categoryFilter')?.addEventListener('change', () => this.loadProducts());
        this.form?.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    async loadProducts(page = 1) {
        const search = document.getElementById('searchInput')?.value || '';
        const categoryId = document.getElementById('categoryFilter')?.value || '';

        try {
            const params = new URLSearchParams({ search, category_id: categoryId, page });
            const response = await fetch(`/admin/products/data?${params}`);
            const data = await response.json();

            document.getElementById('productCount').textContent = data.total || 0;
            this.renderTable(data.data);
            this.renderPagination(data);
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }

    renderTable(products) {
        const tbody = document.getElementById('productsTableBody');
        if (!products.length) {
            tbody.innerHTML = '<tr><td colspan="7" class="empty-state">No products found</td></tr>';
            return;
        }

        tbody.innerHTML = products.map(p => `
            <tr>
                <td><img src="${p.image || 'https://via.placeholder.com/60'}" class="table-image"></td>
                <td>${p.name}</td>
                <td>${p.category?.name || '-'}</td>
                <td>₱${Number(p.price).toFixed(2)}</td>
                <td>${p.stock}</td>
                <td><span class="status-badge ${p.is_active ? 'active' : 'inactive'}">${p.is_active ? 'Active' : 'Inactive'}</span></td>
                <td>
                    <button class="btn-action" onclick="editProduct(${p.id})">Edit</button>
                    <button class="btn-action btn-delete" onclick="deleteProduct(${p.id})">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    renderPagination(data) {
        const wrapper = document.getElementById('paginationWrapper');
        if (!data.last_page || data.last_page <= 1) {
            wrapper.innerHTML = '';
            return;
        }

        let html = '<div class="pagination">';
        if (data.current_page > 1) {
            html += `<button onclick="loadProductsPage(${data.current_page - 1})">Previous</button>`;
        }
        html += `<span>Page ${data.current_page} of ${data.last_page}</span>`;
        if (data.current_page < data.last_page) {
            html += `<button onclick="loadProductsPage(${data.current_page + 1})">Next</button>`;
        }
        html += '</div>';
        wrapper.innerHTML = html;
    }

    async loadCategories() {
        try {
            const response = await fetch('/api/categories');
            const categories = await response.json();

            const filter = document.getElementById('categoryFilter');
            const select = document.getElementById('categorySelect');

            const filterHtml = '<option value="">All Categories</option>' +
                categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
            const selectHtml = categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');

            if (filter) filter.innerHTML = filterHtml;
            if (select) select.innerHTML = selectHtml;
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    openProductModal(product = null) {
        this.editingId = product?.id || null;
        document.getElementById('productModalTitle').textContent = product ? 'Edit Product' : 'Add Product';
        this.form.reset();

        if (product) {
            Object.keys(product).forEach(key => {
                const input = this.form.querySelector(`[name="${key}"]`);
                if (input) input.value = product[key];
            });
        }

        this.modal.classList.add('active');
    }

    closeProductModal() {
        this.modal.classList.remove('active');
        this.editingId = null;
    }

    async handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this.form);
        const data = Object.fromEntries(formData.entries());
        data.is_active = formData.has('is_active');

        try {
            const url = this.editingId ? `/admin/products/${this.editingId}` : '/admin/products';
            const method = this.editingId ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();
            if (result.message) {
                this.closeProductModal();
                this.loadProducts();
            }
        } catch (error) {
            console.error('Error saving product:', error);
        }
    }
}

let productsHandler;

document.addEventListener('DOMContentLoaded', () => {
    productsHandler = new ProductsHandler();
});

function openProductModal(product = null) {
    productsHandler?.openProductModal(product);
}

function closeProductModal() {
    productsHandler?.closeProductModal();
}

function editProduct(id) {
    const products = document.querySelectorAll('#productsTableBody tr');
    products.forEach(row => {
        if (row.querySelector('[onclick*="editProduct"]')?.getAttribute('onclick').includes(id)) {
            const cells = row.querySelectorAll('td');
            // Find and open for edit
        }
    });
}

function deleteProduct(id) {
    if (!confirm('Delete this product?')) return;

    fetch(`/admin/products/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': productsHandler.csrfToken },
    }).then(() => productsHandler.loadProducts());
}

function loadProductsPage(page) {
    productsHandler.loadProducts(page);
}