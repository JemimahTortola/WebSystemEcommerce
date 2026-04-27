<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class CheckAdmin extends Command
{
    protected $signature = 'check:admin';
    protected $description = 'Check if admin user exists';

    public function handle()
    {
        $user = User::where('email', 'admin@flourista.com')->first();

        if (!$user) {
            $this->error('Admin user not found!');
            return;
        }

        $this->info("User: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->info("Roles: " . $user->roles->pluck('name')->implode(', '));

        $hasAdmin = $user->roles()->where('name', 'admin')->exists();
        $this->info("Has admin role: " . ($hasAdmin ? 'Yes' : 'No'));
    }
}