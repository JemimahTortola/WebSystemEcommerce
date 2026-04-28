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
        document.getElementById('productName')?.addEventListener('input', (e) => {
            if (!this.editingId) this.generateSlug(e.target.value);
        });
    }

    generateSlug(name) {
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        // Store slug in a hidden field or data attribute for submission
        this.generatedSlug = slug;
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

        tbody.innerHTML = products.map(p => {
            // Handle both storage paths and direct image paths
            let imageSrc = 'https://via.placeholder.com/60';
            if (p.image) {
                if (p.image.startsWith('/images/')) {
                    imageSrc = p.image;
                } else if (p.image.startsWith('products/')) {
                    imageSrc = '/storage/' + p.image;
                } else {
                    imageSrc = p.image;
                }
            }
            
            return `
                <tr>
                    <td><img src="${imageSrc}" class="table-image" onerror="this.src='https://via.placeholder.com/60'"></td>
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
            `;
        }).join('');
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

    async openProductModal(product = null) {
        this.editingId = product?.id || null;
        document.getElementById('productModalTitle').textContent = product ? 'Edit Product' : 'Add Product';
        this.form.reset();
        document.getElementById('currentImage').style.display = 'none';

        if (product) {
            // Populate form fields
            this.form.querySelector('[name="name"]').value = product.name || '';
            this.form.querySelector('[name="price"]').value = product.price || '';
            this.form.querySelector('[name="stock"]').value = product.stock || '';
            this.form.querySelector('[name="description"]').value = product.description || '';
            this.form.querySelector('[name="category_id"]').value = product.category_id || '';
            this.form.querySelector('[name="is_active"]').checked = product.is_active !== false;

            // Show current image if exists
            if (product.image) {
                let imageSrc = product.image;
                if (product.image.startsWith('products/')) {
                    imageSrc = '/storage/' + product.image;
                }
                document.getElementById('currentImagePreview').src = imageSrc;
                document.getElementById('currentImage').style.display = 'block';
            }

            // Store slug for update
            this.generatedSlug = product.slug;
        } else {
            this.generatedSlug = '';
        }

        this.modal.classList.add('active');
    }

    closeProductModal() {
        this.modal.classList.remove('active');
        this.editingId = null;
        this.generatedSlug = '';
    }

    async handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this.form);

        // Add auto-generated slug
        if (!this.editingId || !this.generatedSlug) {
            const name = formData.get('name');
            this.generateSlug(name);
        }
        formData.append('slug', this.generatedSlug);

        // Handle checkbox
        formData.set('is_active', formData.has('is_active') ? '1' : '0');

        // Add method spoofing for PUT (update)
        if (this.editingId) {
            formData.append('_method', 'PUT');
        }

        try {
            const url = this.editingId ? `/admin/products/${this.editingId}` : '/admin/products';

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: formData,
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

    async editProduct(id) {
        try {
            const response = await fetch(`/admin/products/${id}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const product = await response.json();
            this.openProductModal(product);
        } catch (error) {
            console.error('Error loading product:', error);
            alert('Failed to load product details. Please try again.');
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
    productsHandler?.editProduct(id);
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