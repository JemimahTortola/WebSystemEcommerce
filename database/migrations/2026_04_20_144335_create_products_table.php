<?php
/**
 * ==============================================================================
 * MIGRATION FILE - create_products_table.php
 * ==============================================================================
 * 
 * This migration creates the 'products' table for the e-commerce floral shop.
 * Products are the items that customers can buy.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ==============================================================================
 * CreateProductsTable Migration
 * ==============================================================================
 * 
 * Table: products
 * Purpose: Store all products (flowers, bouquets, arrangements) for sale
 */
class CreateProductsTable extends Migration
{
    /**
     * ==============================================================================
     * up() - CREATE the products table
     * ==============================================================================
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            // id - Auto-incrementing primary key
            $table->id();
            
            // category_id - Foreign key to categories table
            // constrained() finds the categories table automatically
            // onDelete('cascade') = delete products if their category is deleted
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            // name - Product name
            $table->string('name');
            
            // slug - URL-friendly version of name (e.g., "red-rose-bouquet")
            // unique() ensures each product has a unique URL
            $table->string('slug')->unique();
            
            // description - Full product description (optional/nullable)
            $table->text('description')->nullable();
            
            // type - Product type (e.g., "bouquet", "arrangement", "single")
            $table->string('type', 50);
            
            // price - Product price
            // decimal(10, 2) = up to 10 digits, 2 after decimal (e.g., 1299.99)
            $table->decimal('price', 10, 2);
            
            // stock - Quantity available in inventory
            $table->integer('stock');
            
            // image - Main product image URL (optional)
            $table->string('image')->nullable();
            
            // is_active - Is the product visible in the shop?
            // default(true) = products are visible by default
            $table->boolean('is_active')->default(true);
            
            // timestamps - created_at and updated_at
            $table->timestamps();
            
            // softDeletes - allows "deleting" without actually removing data
            $table->softDeletes();
        });
    }

    /**
     * ==============================================================================
     * down() - REMOVE the products table
     * ==============================================================================
     */
    public function down()
    {
        // Remove the products table completely
        Schema::dropIfExists('products');
    }
}

/*
 * ==============================================================================
 * UNDERSTANDING FOREIGN KEYS:
 * ==============================================================================
 * 
 * $table->foreignId('category_id')->constrained()
 * 
 * This creates a relationship between products and categories.
 * - 'category_id' = the column in THIS table (products)
 * - constrained() = tells Laravel to find the 'categories' table
 * - onDelete('cascade') = if a category is deleted, also delete its products
 * 
 * Database equivalent:
 * ALTER TABLE products ADD FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE;
 */