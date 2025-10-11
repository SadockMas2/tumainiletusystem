<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Permissions
        $permissions = [
            'creer_compte_membre',
            'gerer_remboursement',
            'gestion_batch',
            'postage_credit',
            'effectuer_depot',
            'effectuer_retrait',
            'verifier_solde',
            'extraire_rapport_caisse',
            'passer_operations_logistique',
            'enregistrer_operation_coffre',
            'extraire_operations_coffre',
            'debourser_credit',
            'autoriser_retrait',
            'paiement_salaire',
            'acces_rapports',
            'dispatching',
            'extraire_rapport_collecte',
            'extraire_rapport_membres',
            'extraire_tous_rapports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles et attribution des permissions
        $rolesPermissions = [
            'Admin' => $permissions, // Super admin a toutes les permissions
            'MembresODP' => ['creer_compte_membre','gerer_remboursement','gestion_batch','postage_credit'],
            'Caissiere' => ['effectuer_depot','effectuer_retrait','verifier_solde','extraire_rapport_caisse'],
            'Comptable' => ['passer_operations_logistique','enregistrer_operation_coffre','extraire_operations_coffre'],
            'ChefBureau' => ['debourser_credit','autoriser_retrait'],
            'Financier' => ['paiement_salaire','acces_rapports'],
            'AgentCollecteur' => ['dispatching','extraire_rapport_collecte'],
            'ConseillerMembres' => ['extraire_rapport_membres'],
            'ControleurAuditeur' => ['extraire_tous_rapports'],
            'ConseilAdministration' => ['extraire_tous_rapports'],
        ];

        foreach ($rolesPermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }
    }
}
