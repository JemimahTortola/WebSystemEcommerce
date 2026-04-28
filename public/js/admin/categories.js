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
        document.getElementById('categoryName')?.addEventListener('input', (e) => {
            if (!this.editingId) this.generateSlug(e.target.value);
        });
    }

    generateSlug(name) {
        this.generatedSlug = name.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
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
            tbody.innerHTML = '<tr><td colspan="3" class="empty-state">No categories found</td></tr>';
            return;
        }

        tbody.innerHTML = categories.map(c => `
            <tr>
                <td>${c.name}</td>
                <td>${c.products_count || 0}</td>
                <td>
                    <button class="btn-action" onclick="editCategory(${c.id})">Edit</button>
                    <button class="btn-action btn-delete" onclick="deleteCategory(${c.id})">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    async openCategoryModal(category = null) {
        this.editingId = category?.id || null;
        document.getElementById('categoryModalTitle').textContent = category ? 'Edit Category' : 'Add Category';
        this.form.reset();

        if (category) {
            // Populate form fields
            this.form.querySelector('[name="name"]').value = category.name || '';
            this.form.querySelector('[name="description"]').value = category.description || '';
            this.generatedSlug = category.slug;
        } else {
            this.generatedSlug = '';
        }

        this.modal.classList.add('active');
    }

    closeCategoryModal() {
        this.modal.classList.remove('active');
        this.editingId = null;
        this.generatedSlug = '';
    }

    async handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this.form);

        // Auto-generate slug
        if (!this.editingId || !this.generatedSlug) {
            const name = formData.get('name');
            this.generateSlug(name);
        }
        formData.append('slug', this.generatedSlug);

        // Add method spoofing for PUT
        if (this.editingId) {
            formData.append('_method', 'PUT');
        }

        try {
            const url = this.editingId ? `/admin/categories/${this.editingId}` : '/admin/categories';

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: formData,
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

    async editCategory(id) {
        try {
            const response = await fetch(`/admin/categories/${id}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const category = await response.json();
            this.openCategoryModal(category);
        } catch (error) {
            console.error('Error loading category:', error);
            alert('Failed to load category details.');
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
    categoriesHandler?.editCategory(id);
}

function deleteCategory(id) {
    if (!confirm('Delete this category?')) return;

    fetch(`/admin/categories/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': categoriesHandler.csrfToken },
    }).then(() => categoriesHandler.loadCategories());
}