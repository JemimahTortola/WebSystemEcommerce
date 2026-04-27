<?php
/**
 * ==============================================================================
 * SEEDER FILE - UserSeeder.php
 * ==============================================================================
 * 
 * What is a Seeder?
 * ----------------
 * A seeder is a file that fills your database with sample/test data.
 * While migrations CREATE the table structure, seeders ADD the data.
 * 
 * Run this with: php artisan db:seed --class=UserSeeder
 * Or run all seeders: php artisan db:seed
 */

namespace Database\Seeders;

// Import necessary Laravel classes
use Illuminate\Database\Seeder;                  // Base seeder class
use Illuminate\Support\Facades\DB;              // Database facade for queries
use Illuminate\Support\Facades\Hash;             // For hashing passwords

/**
 * ==============================================================================
 * UserSeeder Class
 * ==============================================================================
 * 
 * Purpose: Create initial user accounts, roles, and assign admin role
 */
class UserSeeder extends Seeder
{
    /**
     * ==============================================================================
     * run() - The main method that runs when seeder is executed
     * ==============================================================================
     */
    public function run()
    {
        // ==============================================================================
        // Step 1: Create the admin user account
        // ==============================================================================
        
        // Insert into the 'users' table
        DB::table('users')->insert([
            // name - User's display name
            'name' => 'Administrator',
            
            // email - User's login email (test account)
            'email' => 'admin@ecoms-florist.test',
            
            // password - Hash the password for secure storage
            // Hash::make() encrypts the password so it's not stored as plain text
            'password' => Hash::make('password123'),
            
            // phone - Contact number
            'phone' => '09123456789',
            
            // created_at - When the record was created (Laravel helper)
            'created_at' => now(),
            
            // updated_at - When the record was last updated
            'updated_at' => now(),
        ]);

        // ==============================================================================
        // Step 2: Create user roles
        // ==============================================================================
        
        // Insert multiple roles at once (array of arrays)
        DB::table('roles')->insert([
            // First role: admin
            [
                'name' => 'admin',                       // Role name
                'created_at' => now(),                    // Created timestamp
                'updated_at' => now(),                    // Updated timestamp
            ],
            // Second role: customer (regular user)
            [
                'name' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ==============================================================================
        // Step 3: Assign admin role to the user
        // ==============================================================================
        
        // Find the user we just created by email
        // ->first() gets the first matching record
        $userId = DB::table('users')->where('email', 'admin@ecoms-florist.test')->first()->id;
        
        // Find the admin role by name
        $adminRoleId = DB::table('roles')->where('name', 'admin')->first()->id;
        
        // Link user to role in the user_roles table
        DB::table('user_roles')->insert([
            // user_id - The user's ID
            'user_id' => $userId,
            
            // role_id - The role's ID
            'role_id' => $adminRoleId,
        ]);
    }
}

/*
 * ==============================================================================
 * COMMON DATABASE METHODS EXPLAINED:
 * ==============================================================================
 * 
 * DB::table('name')->insert([data])     -> Insert a new record
 * DB::table('name')->where('col', 'val')->first()        -> Get first matching record
 * DB::table('name')->get()             -> Get all records
 * DB::table('name')->update([data])   -> Update records
 * DB::table('name')->delete()         -> Delete records
 * 
 * Hash::make('password')              -> Hash a password (one-way encryption)
 * now()                                -> Get current timestamp
 * 
 * ==============================================================================
 * UNDERSTANDING THE USER-ROLE SYSTEM:
 * ==============================================================================
 * 
 * This application uses a many-to-many relationship for roles:
 * - users table (one user)
 * - roles table (one role)
 * - user_roles table (links users to roles)
 * 
 * A user can have multiple roles (admin, customer, etc.)
 * The user_roles table stores these connections.
 */