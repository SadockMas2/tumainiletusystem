<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FixPermissionsFormatSeeder extends Seeder
{
    public function run()
    {
        // Liste de toutes vos ressources avec leurs noms corrects
        $resources = [
            'UserResource',
            'ClientResource', 
            'MembreResource',
            'CompteResource',
            'TransactionResource',
            'DepotResource',
            'RetraitResource',
            'CreditResource',
            'EmployeResource',
            'SalaireResource',
            'CoffreResource',
            'OperationLogistiqueResource',
            'OperationCoffreResource',
            'AutorisationResource',
            'RapportCaisseResource',
            'RapportCoffreResource',
            'RapportCollecteResource',
            'RapportMembresResource',
            'RapportGeneralResource',
            'CompteBancaireResource',
            'CompteSpecialResource',
            'CompteTransitoireResource',
            'CycleResource',
            'DispatchResource',
            'EpargneResource',
            'GroupeSolidaireResource',
            'HistoriqueCompteSpecialResource',
            'MouvementCreditResource',
            'MouvementResource',
            'TypeCompteResource',
            'CaisseComptableResource',
            'CaisseJournaliereResource',
            'PermissionResource',
            'RoleResource', // ← IMPORTANT: Shield utilise ce nom
        ];

        // Pages avec leurs noms corrects
        $pages = [
            'Dashboard',
            'EditProfile',
            'PermissionManager',
            'HeaderThemeSwitcher',
            'StatsOverview',
        ];

        // Types de permissions pour les ressources
        $resourcePermissions = [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
        ];

        // Supprimer les anciennes permissions
        Permission::query()->delete();

        $permissionsCreated = 0;

        // Créer les permissions pour les ressources (format Shield)
        foreach ($resources as $resource) {
            $resourceName = strtolower($resource);
            foreach ($resourcePermissions as $permission) {
                Permission::create([
                    'name' => $permission . '_' . $resourceName,
                    'guard_name' => 'filament'
                ]);
                $permissionsCreated++;
            }
        }

        // Créer les permissions pour les pages (format Shield)
        foreach ($pages as $page) {
            Permission::create([
                'name' => 'view_' . strtolower($page),
                'guard_name' => 'filament'
            ]);
            $permissionsCreated++;
        }

        $this->command->info("✅ Créées {$permissionsCreated} permissions avec le format correct.");

        // Assigner toutes les permissions au super_admin
        $superAdminRole = Role::where('name', 'super_admin')->where('guard_name', 'filament')->first();
        
        if ($superAdminRole) {
            $allPermissions = Permission::where('guard_name', 'filament')->get();
            $superAdminRole->syncPermissions($allPermissions);
            $this->command->info("✅ Assignées {$allPermissions->count()} permissions au rôle super_admin.");
        }

        // Vérifier l'utilisateur
        $user = \App\Models\User::where('email', 'admintumainiletu@gmail.com')->first();
        if ($user) {
            $user->syncRoles([$superAdminRole]);
            $this->command->info("✅ Rôle super_admin réassigné à l'utilisateur.");
        }

        // Test de vérification
        $this->command->info("🔍 Test des permissions Shield:");
        $this->command->info(" - view_any_roleresource: " . ($user->can('view_any_roleresource') ? '✅ OUI' : '❌ NON'));
        $this->command->info(" - view_any_userresource: " . ($user->can('view_any_userresource') ? '✅ OUI' : '❌ NON'));
    }
}