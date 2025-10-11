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
        if (User::where('email', 'admin@domain.com')->doesntExist()) {
            
            $admin = User::create([
                'name' => 'Super Admin',
                'email' => 'admintumainiletu@gmail.com',
                'password' => bcrypt('Admin123!'), // mot de passe initial à changer
            ]);

            // Assigner le rôle Admin
            $admin->assignRole('Admin');

            $this->command->info('Super Admin créé avec email admintumainiletu@gmail.com et mot de passe Admin123!');
        } else {
            $this->command->info('Super Admin existe déjà.');
        }
    }
}
