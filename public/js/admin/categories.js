// Handles all category-related functionality (add, edit, delete, list)
class CategoriesHandler {
    constructor() {
        // Get the CSRF token from the meta tag (for security)
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        // Get references to the modal popup and form
        this.modal = document.getElementById('categoryModal');
        this.form = document.getElementById('categoryForm');
        // Track which category we're editing (null = adding new)
        this.editingId = null;
        this.init();
    }

    // Initialize - load categories and set up event listeners
    init() {
        this.loadCategories();
        this.bindEvents();
    }

    // Set up event listeners for form submission and slug generation
    bindEvents() {
        // Handle form submission
        this.form?.addEventListener('submit', (e) => this.handleSubmit(e));
        // Auto-generate slug from name when typing (only for new categories)
        document.getElementById('categoryName')?.addEventListener('input', (e) => {
            if (!this.editingId) this.generateSlug(e.target.value);
        });
    }

    // Creates a URL-friendly slug from the category name (e.g., "Fresh Flowers" becomes "fresh-flowers")
    generateSlug(name) {
        this.generatedSlug = name.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    // Loads all categories from the server and displays them in the table
    async loadCategories() {
        try {
            const response = await fetch('/api/categories');
            const categories = await response.json();
            this.renderTable(categories);
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    // Displays all categories in the HTML table
    renderTable(categories) {
        const tbody = document.getElementById('categoriesTableBody');
        // Show empty message if no categories
        if (!categories.length) {
            tbody.innerHTML = '<tr><td colspan="3" class="empty-state">No categories found</td></tr>';
            return;
        }

        // Create HTML for each category row
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

    // Opens the popup modal to add a new category or edit an existing one
    async openCategoryModal(category = null) {
        // Set the editing ID (null = adding new)
        this.editingId = category?.id || null;
        document.getElementById('categoryModalTitle').textContent = category ? 'Edit Category' : 'Add Category';
        this.form.reset();

        if (category) {
            // Fill in the form with the category's current data
            this.form.querySelector('[name="name"]').value = category.name || '';
            this.form.querySelector('[name="description"]').value = category.description || '';
            this.generatedSlug = category.slug;
        } else {
            this.generatedSlug = '';
        }

        // Show the modal popup
        this.modal.classList.add('active');
    }

    // Closes the category modal and resets the editing state
    closeCategoryModal() {
        this.modal.classList.remove('active');
        this.editingId = null;
        this.generatedSlug = '';
    }

    // Handles the form submission (saves category to database)
    async handleSubmit(e) {
        e.preventDefault(); // Prevent default form submission
        const formData = new FormData(this.form);

        // Auto-generate slug from name if not editing or slug is empty
        if (!this.editingId || !this.generatedSlug) {
            const name = formData.get('name');
            this.generateSlug(name);
        }
        formData.append('slug', this.generatedSlug);

        // Laravel needs PUT method for updates, but HTML forms only support GET/POST
        // We spoof it by adding _method field
        if (this.editingId) {
            formData.append('_method', 'PUT');
        }

        try {
            const url = this.editingId ? `/admin/categories/${this.editingId}` : '/admin/categories';
 
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken, // Security token
                },
                body: formData,
            });

            const result = await response.json();
            if (result.message) {
                this.closeCategoryModal();
                this.loadCategories(); // Reload the category list
            }
        } catch (error) {
            console.error('Error saving category:', error);
        }
    }

    // Loads a single category's data for editing
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

// Create a global instance of the CategoriesHandler
let categoriesHandler;

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    categoriesHandler = new CategoriesHandler();
});

// Global functions that the HTML buttons call (they use the categoriesHandler)
function openCategoryModal(category = null) {
    categoriesHandler?.openCategoryModal(category);
}

function closeCategoryModal() {
    categoriesHandler?.closeCategoryModal();
}

function editCategory(id) {
    categoriesHandler?.editCategory(id);
}

// Deletes a category after confirmation
function deleteCategory(id) {
    if (!confirm('Delete this category?')) return;

    fetch(`/admin/categories/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': categoriesHandler.csrfToken },
    }).then(() => categoriesHandler.loadCategories());
}