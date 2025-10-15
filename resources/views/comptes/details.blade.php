<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Compte - Tumaini Letu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .details-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        .stat-card {
            transition: all 0.3s ease;
            border-left: 4px solid;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            background: linear-gradient(90deg, #10b981, #059669);
            height: 8px;
            border-radius: 4px;
            transition: width 1s ease-in-out;
        }
        .timeline-item {
            position: relative;
            padding-left: 2rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #667eea;
        }
        .empty-state {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="w-full max-w-6xl mx-4 my-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-lg mb-4">
                <i class="fas fa-file-invoice-dollar text-3xl text-purple-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-white mb-3">Détails du Compte</h1>
            <p class="text-white/80 text-lg">Vue d'ensemble complète de votre compte</p>
        </div>

        <!-- Main Details Card -->
        <div class="details-card rounded-2xl p-8">
            <!-- Account Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                    Compte <span class="text-purple-600">{{ $compte->numero_compte }}</span>
                </h2>
                <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-600 mb-4">
                    <div class="flex items-center bg-blue-50 rounded-full px-4 py-2">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        <span class="font-medium">{{ $compte->nom }} {{ $compte->prenom }}</span>
                    </div>
                    <div class="flex items-center bg-green-50 rounded-full px-4 py-2">
                        <i class="fas fa-id-card mr-2 text-green-500"></i>
                        <span class="font-medium">{{ $compte->numero_membre }}</span>
                    </div>
                    <div class="flex items-center bg-purple-50 rounded-full px-4 py-2">
                        <i class="fas fa-wallet mr-2 text-purple-500"></i>
                        <span class="font-medium">{{ $compte->devise }}</span>
                    </div>
                </div>
            </div>

            <!-- Account Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Solde Actuel -->
                <div class="stat-card bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-l-blue-400">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-wallet text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-blue-600">
                            {{ number_format($compte->solde, 2, ',', ' ') }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Solde Actuel</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $compte->devise }}</p>
                </div>

                <!-- Statut du Compte -->
                <div class="stat-card bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border-l-green-400">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        @php
                            $statutColor = $compte->statut === 'actif' ? 'text-green-600' : 'text-red-600';
                            $statutIcon = $compte->statut === 'actif' ? 'fa-check' : 'fa-pause';
                        @endphp
                        <span class="text-xl font-bold {{ $statutColor }}">
                            <i class="fas {{ $statutIcon }} mr-1"></i>
                            {{ ucfirst($compte->statut) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Statut du Compte</p>
                    <p class="text-xs text-gray-500 mt-1">État actuel</p>
                </div>

                <!-- Nombre de Crédits -->
                <div class="stat-card bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border-l-purple-400">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-credit-card text-purple-600 text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-purple-600">
                            {{ $compte->credits->count() }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Crédits Actifs</p>
                    <p class="text-xs text-gray-500 mt-1">Total des crédits</p>
                </div>
            </div>

            @if($compte->credits->count() > 0)
                <!-- Quick Stats Grid for Credits -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @php
                        $credit = $compte->credits->first();
                    @endphp
                    <!-- Montant Principal -->
                    <div class="stat-card bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-l-blue-400">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold text-blue-600">
                                {{ number_format($credit->montant_principal, 2, ',', ' ') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Montant Principal</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $compte->devise }}</p>
                    </div>

                    <!-- Taux d'Intérêt -->
                    <div class="stat-card bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border-l-green-400">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-percentage text-green-600 text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold text-green-600">
                                {{ $credit->taux_interet }}%
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Taux d'Intérêt</p>
                        <p class="text-xs text-gray-500 mt-1">Taux annuel</p>
                    </div>

                    <!-- Montant Total -->
                    <div class="stat-card bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border-l-purple-400">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-invoice-dollar text-purple-600 text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold text-purple-600">
                                {{ number_format($credit->montant_total, 2, ',', ' ') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Montant Total</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $compte->devise }}</p>
                    </div>
                </div>

                <!-- Progress and Timeline Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Progress Section -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2 text-indigo-500"></i>
                            Progression du Remboursement
                        </h3>
                        
                        @php
                            $progress = (($credit->montant_principal - $credit->montant_total) / $credit->montant_principal) * 100;
                            $progress = max(0, min(100, $progress));
                        @endphp
                        
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Progression</span>
                                <span class="font-semibold">{{ number_format($progress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="progress-bar rounded-full h-3" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <p class="text-sm text-gray-600">Montant Restant</p>
                                <p class="text-lg font-bold text-gray-800">
                                    {{ number_format($credit->montant_total, 2, ',', ' ') }} {{ $compte->devise }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <p class="text-sm text-gray-600">Déjà Remboursé</p>
                                <p class="text-lg font-bold text-green-600">
                                    {{ number_format($credit->montant_principal - $credit->montant_total, 2, ',', ' ') }} {{ $compte->devise }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Section -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-history mr-2 text-blue-500"></i>
                            Chronologie du Crédit
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="timeline-item">
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-semibold text-gray-800">Date d'Octroi</span>
                                        <span class="text-sm text-gray-500 bg-blue-100 px-2 py-1 rounded">
                                            {{ \Carbon\Carbon::parse($credit->date_octroi)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">Début du contrat de crédit</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-semibold text-gray-800">Date d'Échéance</span>
                                        <span class="text-sm text-gray-500 bg-green-100 px-2 py-1 rounded">
                                            {{ \Carbon\Carbon::parse($credit->date_echeance)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">Date limite de remboursement</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-semibold text-gray-800">Durée Totale</span>
                                        <span class="text-sm text-gray-500 bg-purple-100 px-2 py-1 rounded">
                                            {{ $credit->duree }} mois
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">Période de remboursement</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State for No Credits -->
                <div class="empty-state rounded-2xl p-12 text-center mb-8 border-2 border-dashed border-gray-300">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-credit-card text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-600 mb-3">Aucun Crédit Actif</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">
                        Ce compte n'a pas encore de crédit en cours. Vous pouvez demander un nouveau crédit en cliquant sur le bouton ci-dessous.
                    </p>
                    <a 
                        href="{{ route('credits.create', $compte->id) }}" 
                        class="inline-flex items-center bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg"
                    >
                        <i class="fas fa-plus-circle mr-2"></i>
                        Demander un Premier Crédit
                    </a>
                </div>
            @endif

            <!-- Credits History Table -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-list-alt mr-2 text-indigo-500"></i>
                        Historique des Crédits
                    </h3>
                </div>
                
                @if($compte->credits->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Montant Initial
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Montant Restant
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Durée
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date Début
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date Fin
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($compte->credits as $credit)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ number_format($credit->montant_principal, 2, ',', ' ') }} {{ $compte->devise }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium 
                                            {{ $credit->montant_total > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                            {{ number_format($credit->montant_total, 2, ',', ' ') }} {{ $compte->devise }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $credit->duree }} mois</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($credit->date_octroi)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($credit->date_echeance)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'en_cours' => 'bg-yellow-100 text-yellow-800',
                                                'remboursé' => 'bg-green-100 text-green-800',
                                                'en_retard' => 'bg-red-100 text-red-800'
                                            ];
                                            $colorClass = $statusColors[$credit->statut] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                            <i class="fas 
                                                {{ $credit->statut === 'en_cours' ? 'fa-play' : 
                                                   ($credit->statut === 'remboursé' ? 'fa-check' : 'fa-exclamation-triangle') }} 
                                                mr-1">
                                            </i>
                                            {{ ucfirst($credit->statut) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg">Aucun crédit dans l'historique</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-200">
                @if($compte->credits->count() > 0)
                    <a 
                        href="{{ route('credits.payer', $compte->id) }}" 
                        class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center"
                    >
                        <i class="fas fa-credit-card mr-3"></i>
                        Effectuer un Paiement
                    </a>
                @endif
                
                <a 
                    href="{{ route('credits.create', $compte->id) }}" 
                    class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center"
                >
                    <i class="fas fa-plus-circle mr-3"></i>
                    Nouveau Crédit
                </a>
                
                <a 
                    href="{{ url('/admin/comptes') }}" 
                    class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center"
                >
                    <i class="fas fa-arrow-left mr-3"></i>
                    Retour aux Comptes
                </a>
            </div>

            <!-- Security Notice -->
            <div class="mt-6 text-center">
                <div class="inline-flex items-center text-xs text-gray-500 bg-gray-100 rounded-full px-4 py-2">
                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                    Informations sécurisées et confidentielles
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white/60 text-sm">
                &copy; 2025 Tumaini Letu System. Tous droits réservés.
            </p>
        </div>
    </div>

    <script>
        // Animation pour les cartes de statistiques
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Animation de la barre de progression
        document.addEventListener('DOMContentLoaded', function() {
            const progressBar = document.querySelector('.progress-bar');
            if (progressBar) {
                const computedStyle = getComputedStyle(progressBar);
                const finalWidth = computedStyle.width;
                
                progressBar.style.width = '0';
                setTimeout(() => {
                    progressBar.style.width = finalWidth;
                }, 500);
            }
        });
    </script>
</body>
</html>