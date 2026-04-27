<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class MakeAdmin extends Command
{
    protected $signature = 'make:admin {email? : Email address} {password? : Password}';
    protected $description = 'Create or update an admin user';

    public function handle()
    {
        $email = $this->argument('email') ?: 'admin@flourista.com';
        $password = $this->argument('password') ?: 'password123';

        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $user = User::updateOrCreate(
            ['email' => $email],
            ['name' => 'Admin', 'password' => \Illuminate\Support\Facades\Hash::make($password)]
        );

        $user->roles()->syncWithoutDetaching([$adminRole->id]);

        $this->info("Admin user ready!");
        $this->table(['Field', 'Value'], [
            ['Email', $email],
            ['Password', $password],
        ]);
    }
}