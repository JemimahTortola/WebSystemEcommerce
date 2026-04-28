// Simple product form handler
function openProductModal(product = null) {
    const modal = document.getElementById('productModal');
    const form = document.getElementById('productForm');
    const title = document.getElementById('productModalTitle');
    
    form.reset();
    document.getElementById('currentImage').style.display = 'none';
    
    if (product) {
        title.textContent = 'Edit Product';
        form.querySelector('[name="name"]').value = product.name || '';
        form.querySelector('[name="price"]').value = product.price || '';
        form.querySelector('[name="stock"]').value = product.stock || '';
        form.querySelector('[name="description"]').value = product.description || '';
        form.querySelector('[name="category_id"]').value = product.category_id || '';
        form.querySelector('[name="is_active"]').checked = product.is_active !== false;
        
        if (product.image) {
            let imageSrc = product.image.startsWith('products/') ? '/storage/' + product.image : product.image;
            document.getElementById('currentImagePreview').src = imageSrc;
            document.getElementById('currentImage').style.display = 'block';
        }
        
        form.dataset.editingId = product.id;
        form.dataset.slug = product.slug;
    } else {
        title.textContent = 'Add Product';
        delete form.dataset.editingId;
        delete form.dataset.slug;
    }
    
    modal.classList.add('active');
}

function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
}

function submitProductForm() {
    const form = document.getElementById('productForm');
    const formData = new FormData(form);
    
    // CSRF token is not needed in FormData - Laravel handles it via cookies
    
    // Generate slug if not editing or slug not set
    if (!form.dataset.editingId || !form.dataset.slug) {
        const name = formData.get('name');
        if (name) {
            const slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
            formData.append('slug', slug);
        }
    } else {
        formData.append('slug', form.dataset.slug);
    }
    
    // Handle checkbox
    formData.set('is_active', formData.has('is_active') ? '1' : '0');
    
    // Add method spoofing for edit
    const editingId = form.dataset.editingId;
    if (editingId) {
        formData.append('_method', 'PUT');
    }
    
    const url = editingId ? `/admin/products/${editingId}` : '/admin/products';
    
    fetch(url, {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(result => {
        if (result.message) {
            closeProductModal();
            loadProducts();
        } else {
            alert('Save failed: ' + JSON.stringify(result));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving product. Check console.');
    });
}

function editProduct(id) {
    fetch(`/admin/products/${id}`)
    .then(response => response.json())
    .then(product => openProductModal(product))
    .catch(error => {
        console.error('Error loading product:', error);
        alert('Failed to load product details.');
    });
}

function deleteProduct(id) {
    if (!confirm('Delete this product?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    fetch(`/admin/products/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken },
    })
    .then(() => loadProducts())
    .catch(error => console.error('Error deleting:', error));
}

function loadProducts(page = 1) {
    const search = document.getElementById('searchInput')?.value || '';
    const categoryId = document.getElementById('categoryFilter')?.value || '';
    
    const params = new URLSearchParams({ search, category_id: categoryId, page });
    
    fetch(`/admin/products/data?${params}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('productCount').textContent = data.total || 0;
        renderTable(data.data);
        renderPagination(data);
    })
    .catch(error => console.error('Error loading products:', error));
}

function renderTable(products) {
    const tbody = document.getElementById('productsTableBody');
    if (!products || !products.length) {
        tbody.innerHTML = '<tr><td colspan="7" class="empty-state">No products found</td></tr>';
        return;
    }
    
    tbody.innerHTML = products.map(p => {
        let imageSrc = 'https://via.placeholder.com/60';
        if (p.image) {
            imageSrc = p.image.startsWith('products/') ? '/storage/' + p.image : p.image;
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

function renderPagination(data) {
    const wrapper = document.getElementById('paginationWrapper');
    if (!data.last_page || data.last_page <= 1) {
        wrapper.innerHTML = '';
        return;
    }
    
    let html = '<div class="pagination">';
    if (data.current_page > 1) {
        html += `<button onclick="loadProducts(${data.current_page - 1})">Previous</button>`;
    }
    html += `<span>Page ${data.current_page} of ${data.last_page}</span>`;
    if (data.current_page < data.last_page) {
        html += `<button onclick="loadProducts(${data.current_page + 1})">Next</button>`;
    }
    html += '</div>';
    wrapper.innerHTML = html;
}

function loadCategories() {
    fetch('/api/categories')
    .then(response => response.json())
    .then(categories => {
        const filterHtml = '<option value="">All Categories</option>' +
            categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
        const selectHtml = categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
        
        document.getElementById('categoryFilter').innerHTML = filterHtml;
        document.getElementById('categorySelect').innerHTML = selectHtml;
    })
    .catch(error => console.error('Error loading categories:', error));
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    loadCategories();
    
    // Bind search and filter events
    document.getElementById('searchInput')?.addEventListener('input', () => loadProducts());
    document.getElementById('categoryFilter')?.addEventListener('change', () => loadProducts());
});
