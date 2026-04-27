class CategoriesHandler {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.modal = document.getElementById('categoryModal');
        this.form = document.getElementById('categoryForm');
        this.editingId = null;
        this.init();
    }

    init() {
        this.loadCategories();
        this.bindEvents();
    }

    bindEvents() {
        this.form?.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    async loadCategories() {
        try {
            const response = await fetch('/api/categories');
            const categories = await response.json();
            this.renderTable(categories);
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    renderTable(categories) {
        const tbody = document.getElementById('categoriesTableBody');
        if (!categories.length) {
            tbody.innerHTML = '<tr><td colspan="4" class="empty-state">No categories found</td></tr>';
            return;
        }

        tbody.innerHTML = categories.map(c => `
            <tr>
                <td>${c.name}</td>
                <td>${c.slug}</td>
                <td>${c.products_count || 0}</td>
                <td>
                    <button class="btn-action" onclick="editCategory(${c.id})">Edit</button>
                    <button class="btn-action btn-delete" onclick="deleteCategory(${c.id})">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    openCategoryModal(category = null) {
        this.editingId = category?.id || null;
        document.getElementById('categoryModalTitle').textContent = category ? 'Edit Category' : 'Add Category';
        this.form.reset();

        if (category) {
            Object.keys(category).forEach(key => {
                const input = this.form.querySelector(`[name="${key}"]`);
                if (input) input.value = category[key];
            });
        }

        this.modal.classList.add('active');
    }

    closeCategoryModal() {
        this.modal.classList.remove('active');
        this.editingId = null;
    }

    async handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this.form);
        const data = Object.fromEntries(formData.entries());

        try {
            const url = this.editingId ? `/admin/categories/${this.editingId}` : '/admin/categories';
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
                this.closeCategoryModal();
                this.loadCategories();
            }
        } catch (error) {
            console.error('Error saving category:', error);
        }
    }
}

let categoriesHandler;

document.addEventListener('DOMContentLoaded', () => {
    categoriesHandler = new CategoriesHandler();
});

function openCategoryModal(category = null) {
    categoriesHandler?.openCategoryModal(category);
}

function closeCategoryModal() {
    categoriesHandler?.closeCategoryModal();
}

function editCategory(id) {
    // Fetch and edit
}

function deleteCategory(id) {
    if (!confirm('Delete this category?')) return;

    fetch(`/admin/categories/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': categoriesHandler.csrfToken },
    }).then(() => categoriesHandler.loadCategories());
}