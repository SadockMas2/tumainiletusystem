<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accorder un Crédit - Tumaini Letu</title>
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
        .approval-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        .info-card {
            transition: all 0.3s ease;
            border-left: 4px solid;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="w-full max-w-4xl mx-4 my-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-lg mb-4">
                <i class="fas fa-hand-holding-usd text-3xl text-purple-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-white mb-3">Accorder un Crédit</h1>
            <p class="text-white/80 text-lg">Examen et approbation de la demande</p>
        </div>

        <!-- Main Card -->
        <div class="approval-card rounded-2xl p-8">
            <!-- Credit Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="info-card bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-l-blue-400">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-blue-600">
                            {{ number_format($credit->montant_principal, 2, ',', ' ') }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Montant Demandé</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $credit->devise }}</p>
                </div>

                <div class="info-card bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border-l-green-400">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-percentage text-green-600 text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-green-600">
                            {{ $credit->taux_interet }}%
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Taux d'Intérêt</p>
                    <p class="text-xs text-gray-500 mt-1">Taux annuel proposé</p>
                </div>

                <div class="info-card bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border-l-purple-400">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-invoice-dollar text-purple-600 text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-purple-600">
                            {{ number_format($credit->montant_total, 2, ',', ' ') }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Montant Total</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $credit->devise }}</p>
                </div>
            </div>

            <!-- Client and Account Information -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-circle mr-2 text-indigo-500"></i>
                    Informations du Client
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nom du Client</p>
                        <p class="font-semibold text-gray-800">{{ $credit->compte->nom }} {{ $credit->compte->prenom }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Numéro de Compte</p>
                        <p class="font-semibold text-gray-800">{{ $credit->compte->numero_compte }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Solde Actuel</p>
                        <p class="font-semibold text-gray-800">
                            {{ number_format($credit->compte->solde, 2, ',', ' ') }} {{ $credit->devise }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date de Demande</p>
                        <p class="font-semibold text-gray-800">
                            @if($credit->date_demande)
                                {{ $credit->date_demande->format('d/m/Y H:i') }}
                            @else
                                <span class="text-orange-500">Non définie</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Statut de la Demande</p>
                        @php
                            $statutClasses = [
                                'en_attente' => 'text-yellow-600',
                                'approuve' => 'text-green-600', 
                                'rejete' => 'text-red-600'
                            ];
                            $statutClass = $statutClasses[$credit->statut_demande] ?? 'text-gray-600';
                        @endphp
                        <p class="font-semibold {{ $statutClass }}">
                            {{ ucfirst($credit->statut_demande) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Durée Proposée</p>
                        <p class="font-semibold text-gray-800">
                            @php
                                $duree = $credit->duree;
                            @endphp
                            @if($duree > 0)
                                {{ $duree }} mois
                            @else
                                <span class="text-orange-500">Non définie</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Impact Analysis -->
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-200 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-orange-500"></i>
                    Analyse d'Impact
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-4 border border-yellow-200">
                        <p class="text-sm text-gray-600">Nouveau Solde après Approbation</p>
                        <p class="text-xl font-bold text-green-600">
                            {{ number_format($credit->compte->solde + $credit->montant_principal, 2, ',', ' ') }} {{ $credit->devise }}
                        </p>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-yellow-200">
                        <p class="text-sm text-gray-600">Intérêts à Payer</p>
                        <p class="text-xl font-bold text-orange-600">
                            {{ number_format($credit->montant_total - $credit->montant_principal, 2, ',', ' ') }} {{ $credit->devise }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Vérification de l'éligibilité -->
            @if($credit->statut_demande !== 'en_attente')
                <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 border border-red-200 mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                        Demande Non Traitable
                    </h3>
                    <p class="text-gray-700">
                        Cette demande de crédit a déjà été traitée. Statut actuel: 
                        @php
                            $statutClasses = [
                                'en_attente' => 'text-yellow-600',
                                'approuve' => 'text-green-600', 
                                'rejete' => 'text-red-600'
                            ];
                            $statutClass = $statutClasses[$credit->statut_demande] ?? 'text-gray-600';
                        @endphp
                        <strong class="{{ $statutClass }}">{{ ucfirst($credit->statut_demande) }}</strong>
                    </p>
                    @if($credit->motif_rejet)
                        <div class="mt-4 bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-sm text-gray-600 font-medium">Motif du rejet:</p>
                            <p class="text-gray-700 mt-1">{{ $credit->motif_rejet }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Approval Form -->
            @if($credit->statut_demande === 'en_attente')
            <form action="{{ route('credits.traiter-approbation', $credit->id) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Approve Button -->
                    <button 
                        type="submit"
                        name="action"
                        value="approuver"
                        class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center"
                        onclick="return confirm('Êtes-vous sûr de vouloir approuver ce crédit? Le solde du compte sera augmenté.')"
                    >
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        Approuver le Crédit
                    </button>

                    <!-- Reject Button -->
                    <button 
                        type="button"
                        id="rejectBtn"
                        class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center"
                    >
                        <i class="fas fa-times-circle mr-3 text-xl"></i>
                        Rejeter la Demande
                    </button>
                </div>

                <!-- Rejection Reason (Hidden by default) -->
                <div id="rejectionSection" class="hidden bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 border border-red-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-comment-alt mr-2 text-red-500"></i>
                        Motif du Rejet
                    </h4>
                    <textarea 
                        name="motif_rejet" 
                        rows="4"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Veuillez indiquer le motif du rejet de cette demande..."
                        required
                    ></textarea>
                    <div class="mt-4 flex justify-end space-x-4">
                        <button 
                            type="button"
                            id="cancelReject"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg transition-all duration-200"
                        >
                            Annuler
                        </button>
                        <button 
                            type="submit"
                            name="action"
                            value="rejeter"
                            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg transition-all duration-200"
                            onclick="return confirm('Êtes-vous sûr de vouloir rejeter cette demande?')"
                        >
                            Confirmer le Rejet
                        </button>
                    </div>
                </div>
            </form>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-200">
                <a 
                    href="{{ route('comptes.details', $credit->compte_id) }}" 
                    class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center"
                >
                    <i class="fas fa-eye mr-3"></i>
                    Voir Détails du Compte
                </a>
                
                @if($credit->statut_demande === 'en_attente')
                <form action="{{ route('credits.annuler', $credit->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center"
                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette demande?')"
                    >
                        <i class="fas fa-times mr-3"></i>
                        Annuler la Demande
                    </button>
                </form>
                @endif
                
                <a 
                    href="{{ url('/admin/comptes') }}" 
                    class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center"
                >
                    <i class="fas fa-arrow-left mr-3"></i>
                    Retour aux Comptes
                </a>
            </div>

            <!-- Security Notice -->
            <div class="mt-6 text-center">
                <div class="inline-flex items-center text-xs text-gray-500 bg-gray-100 rounded-full px-4 py-2">
                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                    Opération sécurisée - Toutes les actions sont tracées
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gestion de l'affichage de la section de rejet
        const rejectBtn = document.getElementById('rejectBtn');
        const cancelReject = document.getElementById('cancelReject');
        const rejectionSection = document.getElementById('rejectionSection');

        if (rejectBtn) {
            rejectBtn.addEventListener('click', function() {
                rejectionSection.classList.remove('hidden');
                this.classList.add('hidden');
            });
        }

        if (cancelReject) {
            cancelReject.addEventListener('click', function() {
                rejectionSection.classList.add('hidden');
                rejectBtn.classList.remove('hidden');
            });
        }
    </script>
</body>
</html>