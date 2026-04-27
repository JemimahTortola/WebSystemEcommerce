<?php
/**
 * ==============================================================================
 * MIGRATION FILE - create_users_table.php
 * ==============================================================================
 * 
 * What is a Migration?
 * -------------------
 * A migration is like a version control for your database.
 * It defines how your database tables should look and what columns they have.
 * 
 * Each migration file creates OR modifies a database table.
 * You can run migrations to create tables, and rollback to remove them.
 * 
 * Run this with: php artisan migrate
 * Rollback with: php artisan migrate:rollback
 */

use Illuminate\Database\Migrations\Migration;          // Laravel's Migration base class
use Illuminate\Database\Schema\Blueprint;              // Used to define table columns
use Illuminate\Support\Facades\Schema;                 // Schema builder for tables

/**
 * ==============================================================================
 * CreateUsersTable Migration Class
 * ==============================================================================
 * 
 * This migration creates the 'users' table in the database.
 * The users table stores all registered user accounts.
 */
class CreateUsersTable extends Migration
{
    /**
     * ==============================================================================
     * up() Method - Run when executing: php artisan migrate
     * ==============================================================================
     * This method CREATES the table in the database.
     */
    public function up()
    {
        // Create the 'users' table with the following columns
        Schema::create('users', function (Blueprint $table) {
            // $table->id() - Auto-incrementing primary key (1, 2, 3...)
            // Acts like: id INT PRIMARY KEY AUTO_INCREMENT
            $table->id();
            
            // $table->string('name') - VARCHAR column for user's name
            // Acts like: name VARCHAR(255) NOT NULL
            $table->string('name');
            
            // $table->string('email')->unique() - Email must be unique (no duplicates)
            // Acts like: email VARCHAR(255) UNIQUE NOT NULL
            $table->string('email')->unique();
            
            // $table->string('password') - Hashed password storage
            $table->string('password');
            
            // $table->rememberToken() - For "Remember Me" login feature
            // Stores a token for persistent login sessions
            $table->rememberToken();
            
            // $table->string('phone', 50)->nullable() - Optional phone number
            // nullable() means this field is NOT required
            $table->string('phone', 50)->nullable();
            
            // $table->timestamps() - Adds created_at and updated_at columns
            // Automatically tracks when records are created/updated
            $table->timestamps();
            
            // $table->softDeletes() - Adds deleted_at column for soft deletes
            // Instead of actually deleting, we mark the record as deleted
            // This preserves data even when user is "deleted"
            $table->softDeletes();
        });
    }

    /**
     * ==============================================================================
     * down() Method - Run when executing: php artisan migrate:rollback
     * ==============================================================================
     * This method REMOVES/REVERSES the table creation.
     * Used to undo the up() method.
     */
    public function down()
    {
        // Drop the users table if it exists
        // This completely removes the table and all its data
        Schema::dropIfExists('users');
    }
}

/*
 * ==============================================================================
 * COMMON COLUMN TYPES EXPLAINED:
 * ==============================================================================
 * 
 * $table->id()              -> Auto-incrementing big integer primary key
 * $table->string('name')    -> VARCHAR(255)
 * $table->string('name', 100) -> VARCHAR(100)
 * $table->text('content')   -> Long text for paragraphs
 * $table->integer('age')    -> Integer number
 * $table->decimal('price', 10, 2) -> Decimal with precision (10 total, 2 decimal places)
 * $table->boolean('active')  -> TRUE/FALSE (0 or 1)
 * $table->date('birthday')  -> Date (YYYY-MM-DD)
 * $table->datetime('created_at') -> Date and time
 * $table->timestamp('expires_at') -> Timestamp
 * $table->text('description')-> Long text field
 * $table->json('options')   -> JSON data type
 * 
 * COMMON COLUMN MODIFIERS:
 * ->nullable()    -> Field is NOT required (can be empty)
 * ->default('value') -> Set a default value if not provided
 * ->unique()      -> Values must be unique (no duplicates)
 * ->unsigned()   -> Only positive numbers
 * ->comment('My comment') -> Add a comment to the column
 * ->first()       -> Place column first in table
 * ->after('column') -> Place after specific column
 * 
 * FOREIGN KEY RELATIONSHIPS:
 * $table->foreignId('user_id')->constrained()->onDelete('cascade');
 *   -> Creates a foreign key linking to another table
 *   -> constrained() automatically finds the related table
 *   -> onDelete('cascade') deletes related records when parent is deleted
 */