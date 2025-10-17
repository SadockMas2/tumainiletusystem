<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Nettoyer les anciens rôles et permissions
        Role::query()->delete();
        Permission::query()->delete();

        // Créer le rôle super_admin
        $superAdminRole = Role::create([
            'name' => 'super_admin', 
            'guard_name' => 'filament'
        ]);

        // Créer les autres rôles
        $roles = [
            'Admin',
            'MembresODP',
            'Caissiere', 
            'Comptable',
            'ChefBureau',
            'Financier',
            'AgentCollecteur',
            'ConseillerMembres',
            'ControleurAuditeur',
            'ConseilAdministration',
        ];

        foreach ($roles as $roleName) {
            Role::create([
                'name' => $roleName, 
                'guard_name' => 'filament'
            ]);
        }

        // Assigner toutes les permissions à super_admin
        $allPermissions = Permission::where('guard_name', 'filament')->get();
        $superAdminRole->syncPermissions($allPermissions);

        $this->command->info('Rôles créés avec succès. super_admin a toutes les permissions.');
    }
}