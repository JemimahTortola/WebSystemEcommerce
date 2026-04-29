// Opens the popup form to add a new product or edit an existing one
// If 'product' is provided, we're editing; otherwise, we're adding new
function openProductModal(product = null) {
    const modal = document.getElementById('productModal');
    const form = document.getElementById('productForm');
    const title = document.getElementById('productModalTitle');
    
    // Clear the form and hide the current image preview
    form.reset();
    document.getElementById('currentImage').style.display = 'none';
    
    // If we're editing an existing product
    if (product) {
        title.textContent = 'Edit Product';
        // Fill in all the form fields with the product's current values
        form.querySelector('[name="name"]').value = product.name || '';
        form.querySelector('[name="price"]').value = product.price || '';
        form.querySelector('[name="stock"]').value = product.stock || '';
        form.querySelector('[name="description"]').value = product.description || '';
        form.querySelector('[name="category_id"]').value = product.category_id || '';
        form.querySelector('[name="type"]').value = product.type || '';
        form.querySelector('[name="is_active"]').checked = product.is_active !== false;
        
        // If the product has an image, show it in the preview area
        if (product.image) {
            let imageSrc = product.image.startsWith('products/') ? '/storage/' + product.image : product.image;
            document.getElementById('currentImagePreview').src = imageSrc;
            document.getElementById('currentImage').style.display = 'block';
        }
        
        // Store the product ID and slug in the form for later use when saving
        form.dataset.editingId = product.id;
        form.dataset.slug = product.slug;
    } else {
        // We're adding a new product, so set title to "Add Product"
        title.textContent = 'Add Product';
        // Remove any stored editing data
        delete form.dataset.editingId;
        delete form.dataset.slug;
    }
    
    // Show the popup modal
    modal.classList.add('active');
}

// Closes the popup form
function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
}

// Handles saving the product (both adding new and editing existing)
function submitProductForm() {
    const form = document.getElementById('productForm');
    // Collect all form data including file uploads
    const formData = new FormData(form);
    
    // Add CSRF token for security (prevents unauthorized form submissions)
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        formData.append('_token', csrfToken);
    }
    
    // Generate a URL-friendly slug from the product name (e.g., "Red Rose" becomes "red-rose")
    // Only generate if adding new or slug isn't set
    if (!form.dataset.editingId || !form.dataset.slug) {
        const name = formData.get('name');
        if (name) {
            const slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
            formData.append('slug', slug);
        }
    } else {
        // Use the existing slug when editing
        formData.append('slug', form.dataset.slug);
    }
    
    // Convert checkbox to 1 (checked) or 0 (unchecked) for the database
    formData.set('is_active', formData.has('is_active') ? '1' : '0');
    
    // If editing, we need to tell Laravel to use PUT method (HTML forms only support GET/POST)
    const editingId = form.dataset.editingId;
    if (editingId) {
        formData.append('_method', 'PUT');
    }
    
    // Set the URL - different for editing vs adding
    const url = editingId ? `/admin/products/${editingId}` : '/admin/products';
    
    // Send the form data to the server
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken  // Security token in header too
        },
        credentials: 'same-origin'  // Send cookies with request
    })
    .then(response => response.json())  // Convert response to JSON
    .then(result => {
        if (result.message) {
            // Success! Close the form and reload the product list
            closeProductModal();
            loadProducts();
        } else {
            // Something went wrong, show error
            alert('Save failed: ' + JSON.stringify(result));
        }
    })
    .catch(error => {
        // Network or other error
        console.error('Error:', error);
        alert('Error saving product. Check console.');
    });
}

// Loads a single product's data and opens the edit form
function editProduct(id) {
    fetch(`/admin/products/${id}`, {
        credentials: 'same-origin'  // Send cookies with request
    })
    .then(response => response.json())
    .then(product => openProductModal(product))  // Open form with product data
    .catch(error => {
        console.error('Error loading product:', error);
        alert('Failed to load product details.');
    });
}

// Deletes a product after confirming with the user
function deleteProduct(id) {
    // Ask user to confirm before deleting
    if (!confirm('Delete this product?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Send DELETE request (spoofed as POST since HTML doesn't support DELETE)
    fetch(`/admin/products/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        body: new URLSearchParams({ '_method': 'DELETE', '_token': csrfToken }),
        credentials: 'same-origin'
    })
    .then(() => loadProducts())  // Reload the product list
    .catch(error => console.error('Error deleting:', error));
}

// Loads the product list from the server with search and filter
// 'page' parameter is for pagination (default is page 1)
function loadProducts(page = 1) {
    // Get the current search text and selected category filter
    const search = document.getElementById('searchInput')?.value || '';
    const categoryId = document.getElementById('categoryFilter')?.value || '';
    
    // Build URL parameters for search, filter, and pagination
    const params = new URLSearchParams({ search, category_id: categoryId, page });
    
    // Fetch products from the server
    fetch(`/admin/products/data?${params}`)
    .then(response => response.json())
    .then(data => {
        // Update the product count and display the products in the table
        document.getElementById('productCount').textContent = data.total || 0;
        renderTable(data.data);
        renderPagination(data);
    })
    .catch(error => console.error('Error loading products:', error));
}

// Displays the list of products in the HTML table
function renderTable(products) {
    const tbody = document.getElementById('productsTableBody');
    // Show empty message if no products
    if (!products || !products.length) {
        tbody.innerHTML = '<tr><td colspan="7" class="empty-state">No products found</td></tr>';
        return;
    }
    
    // Create HTML for each product row
    tbody.innerHTML = products.map(p => {
        // Set image source, add /storage/ prefix if needed
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

// Creates the pagination buttons (Previous, page numbers, Next)
function renderPagination(data) {
    const wrapper = document.getElementById('paginationWrapper');
    // Hide pagination if there's only one page
    if (!data.last_page || data.last_page <= 1) {
        wrapper.innerHTML = '';
        return;
    }
    
    // Build pagination HTML
    let html = '<div class="pagination">';
    // Show Previous button if not on first page
    if (data.current_page > 1) {
        html += `<button onclick="loadProducts(${data.current_page - 1})">Previous</button>`;
    }
    html += `<span>Page ${data.current_page} of ${data.last_page}</span>`;
    // Show Next button if not on last page
    if (data.current_page < data.last_page) {
        html += `<button onclick="loadProducts(${data.current_page + 1})">Next</button>`;
    }
    html += '</div>';
    wrapper.innerHTML = html;
}

// Loads categories from the server and fills the dropdown menus
function loadCategories() {
    fetch('/api/categories')
    .then(response => response.json())
    .then(categories => {
        // Build HTML for the filter dropdown (in the table header)
        const filterHtml = '<option value="">All Categories</option>' +
            categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
        // Build HTML for the form dropdown (in the add/edit form)
        const selectHtml = categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
        
        // Update both dropdowns
        document.getElementById('categoryFilter').innerHTML = filterHtml;
        document.getElementById('categorySelect').innerHTML = selectHtml;
    })
    .catch(error => console.error('Error loading categories:', error));
}

// Run this code when the page finishes loading
document.addEventListener('DOMContentLoaded', () => {
    // Load the initial product list and categories
    loadProducts();
    loadCategories();
    
    // Make the search box reload products as you type
    document.getElementById('searchInput')?.addEventListener('input', () => loadProducts());
    // Make the category filter reload products when changed
    document.getElementById('categoryFilter')?.addEventListener('change', () => loadProducts());
});
