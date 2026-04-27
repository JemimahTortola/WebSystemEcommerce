<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class CreateAdminUser extends Command
{
    protected $signature = 'make:admin {email?} {password?}';
    protected $description = 'Create an admin user';

    public function handle()
    {
        $email = $this->argument('email') ?: 'admin@flourista.com';
        $password = $this->argument('password') ?: 'password123';

        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $user = User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
        ]);

        $user->roles()->attach($adminRole->id);

        $this->info("Admin user created!");
        $this->table(['Field', 'Value'], [
            ['Email', $email],
            ['Password', $password],
        ]);
    }
}