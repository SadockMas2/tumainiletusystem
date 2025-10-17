<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Vérifie si un utilisateur Admin existe déjà
        $user = User::where('email', 'admintumainiletu@gmail.com')->first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'admintumainiletu@gmail.com',
                'password' => bcrypt('Admin123!'),
            ]);
            $this->command->info('Super Admin créé.');
        }

        // Assigner le rôle super_admin
        $superAdminRole = Role::where('name', 'super_admin')->where('guard_name', 'filament')->first();
        
        if ($superAdminRole) {
            $user->syncRoles([$superAdminRole]);
            $this->command->info('Rôle super_admin assigné avec succès.');
        } else {
            $this->command->error('Le rôle super_admin n\'existe pas!');
        }
    }
}