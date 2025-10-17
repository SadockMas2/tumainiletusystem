<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //         // User::factory(10)->create();
        // // User::factory()->create([
        // //     'name' => 'Test',
        // //     'prenom' => 'User',
        // //     'email' => 'test@example.com',
        // //     'login' => 'testuser',
        // //     'password' => bcrypt('password'),
        // //     'idprofil' => 1,
        // //     'status' => 'actif',
        // ]);

        $this->call(TypeComptesSeeder::class);
        $this->call([

    RolesAndPermissionsSeeder::class, // d’abord les rôles & permissions
    SuperAdminSeeder::class,     
    FixPermissionsFormatSeeder::class,     // puis le super admin
    ]);


        

    }
    
}
