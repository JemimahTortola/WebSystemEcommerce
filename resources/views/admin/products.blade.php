@extends('layouts.admin')  {{-- Uses the admin layout template --}}

@section('title', 'Products - Flourista Admin')  {{-- Browser tab title --}}

@section('page-title', 'Products')  {{-- Page heading in top bar --}}

{{-- Load product-specific styles --}}
@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/products.css') }}">
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Products</h2>
        <p>Total: <span id="productCount">0</span> products</p>  {{-- Dynamic count updated by JS --}}
    </div>
    <div class="header-right">
        {{-- Button to open the add product modal --}}
        <button class="btn btn-primary" onclick="openProductModal()">Add Product</button>
    </div>
</div>

<div class="filters-bar">
    <input type="text" id="searchInput" placeholder="Search products...">  {{-- Search box updated by JS --}}
    <select id="categoryFilter">  {{-- Category dropdown filled by JS --}}
        <option value="">All Categories</option>
    </select>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="productsTable">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productsTableBody"></tbody>
    </table>
</div>

<div class="pagination-wrapper" id="paginationWrapper"></div>

{{-- Popup modal for adding/editing products --}}
<div class="modal" id="productModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2 id="productModalTitle">Add Product</h2>
            <button class="modal-close" onclick="closeProductModal()">×</button>
        </div>
        {{-- Form with file upload support (enctype required for images) --}}
        <form id="productForm" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="productName" required>  {{-- Product name field --}}
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" id="categorySelect" required></select>  {{-- Filled by JS --}}
                </div>
                <div class="form-group">
                    <label>Price (₱)</label>
                    <input type="number" name="price" step="0.01" required>  {{-- Decimal input for price --}}
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" required>  {{-- Quantity in stock --}}
                </div>
            </div>
                <div class="form-group">
                    <label>Price (₱)</label>
                    <input type="number" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" required>
                </div>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" id="productImage" accept="image/*">  {{-- File picker for product image --}}
                <div id="currentImage" style="margin-top: 8px; display: none;">
                    <small>Current: <img src="" id="currentImagePreview" style="width: 60px; height: 60px; object-fit: cover;"></small>  {{-- Shows existing image when editing --}}
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"></textarea>  {{-- Optional product description --}}
            </div>
            <div class="checkbox-group">
                <input type="checkbox" name="is_active" id="productActive" checked>  {{-- Checkbox to show/hide product --}}
                <label for="productActive">Active</label>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeProductModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitProductForm()">Save</button>  {{-- Calls JS function --}}
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/products.js') }}"></script>
@endsection