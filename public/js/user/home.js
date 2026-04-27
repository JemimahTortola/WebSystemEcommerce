class HomeHandler {
    constructor() {
        this.init();
    }

    init() {
        this.loadCategories();
        this.loadFeaturedProducts();
    }

    async loadCategories() {
        try {
            const response = await fetch('/api/categories', {
                headers: { 'Accept': 'application/json' }
            });
            const categories = await response.json();
            this.renderCategories(categories);
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    renderCategories(categories) {
        const grid = document.getElementById('categoriesGrid');
        if (!grid || !categories || categories.length === 0) return;

        grid.innerHTML = categories.map(cat => `
            <div class="category-card" data-id="${cat.id}">
                <div class="category-icon">${cat.icon || '🌸'}</div>
                <h3>${cat.name}</h3>
            </div>
        `).join('');

        grid.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', () => {
                window.location.href = `/shop?category=${card.dataset.id}`;
            });
        });
    }

    async loadFeaturedProducts() {
        try {
            const response = await fetch('/api/featured-products', {
                headers: { 'Accept': 'application/json' }
            });
            const products = await response.json();
            this.renderFeaturedProducts(products);
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }

    renderFeaturedProducts(products) {
        const grid = document.getElementById('featuredGrid');
        if (!grid || !products || products.length === 0) return;

        grid.innerHTML = products.map(product => `
            <div class="product-card" data-id="${product.id}">
                <img class="product-image" src="${product.image || '/images/placeholder.jpg'}" alt="${product.name}">
                <div class="product-details">
                    <div class="product-name">${product.name}</div>
                    <div class="product-price">₱${product.price}</div>
                </div>
            </div>
        `).join('');

        grid.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', () => {
                window.location.href = `/shop/${card.dataset.id}`;
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new HomeHandler();
});