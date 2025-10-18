<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Crédit - Tumaini Letu</title>
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
        .credit-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .info-item {
            transition: all 0.3s ease;
        }
        .info-item:hover {
            transform: translateY(-2px);
        }
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="w-full max-w-4xl mx-4">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-lg mb-4">
                <i class="fas fa-hand-holding-usd text-3xl text-purple-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-white mb-3">Demande de Crédit</h1>
            <p class="text-white/80 text-lg">Système de Gestion Financière Tumaini Letu</p>
        </div>

        <!-- Main Card -->
        <div class="credit-card rounded-2xl p-8">
            <!-- Account Overview -->
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
                </div>
            </div>

            <!-- Account Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="info-item bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-wallet text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Solde Actuel</p>
                            <p class="text-xl font-bold text-gray-800">
                                {{ number_format($compte->solde, 2, ',', ' ') }} {{ $compte->devise }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="info-item bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Devise du Compte</p>
                            <p class="text-xl font-bold text-gray-800">{{ $compte->devise }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Credit Application Form -->
            <form action="{{ route('credits.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="compte_id" value="{{ $compte->id }}">

                <!-- Form Header -->
                <div class="text-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-edit mr-2 text-indigo-500"></i>
                        Informations du Crédit
                    </h3>
                    <p class="text-gray-600 mt-2">Remplissez les détails de votre demande de crédit</p>
                </div>

                <!-- Amount Input -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <label class="block text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-money-check-alt mr-2 text-green-500"></i>
                        Montant du Crédit
                    </label>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium">{{ $compte->devise }}</span>
                        </div>
                        <input 
                            type="number" 
                            name="montant" 
                            step="0.01"
                            min="0.01"
                            class="form-input block w-full pl-16 pr-4 py-4 text-lg border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                            placeholder="0.00"
                            required
                        >
                    </div>
                    
                    <div class="mt-3 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                        Entrez le montant que vous souhaitez emprunter
                    </div>
                </div>

                <!-- Taux d'Intérêt Input -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <label class="block text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-percentage mr-2 text-orange-500"></i>
                        Taux d'Intérêt 
                    </label>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium">%</span>
                        </div>
                        <input 
                            type="number" 
                            name="taux_interet" 
                            step="0.1"
                            min="0.1"
                            max="90"
                            value="6.72"
                            class="form-input block w-full pl-12 pr-4 py-4 text-lg border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                            placeholder="5.0"
                            required
                        >
                    </div>
                    
                    <div class="mt-3 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                        Taux d'intérêt  (entre 0.1% et 6,72%)
                    </div>
                </div>

                <!-- Duration Input -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <label class="block text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                        Durée de Remboursement
                    </label>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium">
                                <i class="fas fa-clock"></i>
                            </span>
                        </div>
                        <input 
                            type="number" 
                            name="duree" 
                            min="1"
                            max="120"
                            class="form-input block w-full pl-12 pr-4 py-4 text-lg border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                            placeholder="Nombre de mois"
                            required
                        >
                    </div>
                    
                    <div class="mt-3 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                        Durée maximale recommandée: 12 mois
                    </div>
                </div>

                <!-- Simulation du coût -->
                <div id="simulation" class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200 hidden">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-calculator mr-2 text-blue-500"></i>
                        Simulation du Coût
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                            <p class="text-sm text-gray-600">Montant Principal</p>
                            <p id="sim-montant" class="text-lg font-bold text-blue-600">0 {{ $compte->devise }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                            <p class="text-sm text-gray-600">Intérêts Totaux</p>
                            <p id="sim-interets" class="text-lg font-bold text-orange-600">0 {{ $compte->devise }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                            <p class="text-sm text-gray-600">Montant Total</p>
                            <p id="sim-total" class="text-lg font-bold text-green-600">0 {{ $compte->devise }}</p>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                        <div class="text-sm text-yellow-800">
                            <strong>Important:</strong>Le remboursement se fait chaque 7 jours après !!!
                            Assurez-vous de votre capacité de remboursement avant de soumettre votre demande.
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center"
                    >
                        <i class="fas fa-paper-plane mr-3"></i>
                        Soumettre la Demande
                    </button>
                    
                    <a 
                        href="{{ url('/admin/comptes') }}" 
                        class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center"
                    >
                        <i class="fas fa-arrow-left mr-3"></i>
                        Retour aux Comptes
                    </a>
                </div>
            </form>

            <!-- Security Notice -->
            <div class="mt-8 text-center">
                <div class="inline-flex items-center text-xs text-gray-500 bg-gray-100 rounded-full px-4 py-2">
                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                    Votre demande est traitée de manière sécurisée et confidentielle
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white/60 text-sm">
                &copy; 2025 Tumaini Letu System. Service financier de confiance.
            </p>
        </div>
    </div>

    <script>
        // Animation pour les champs de formulaire
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-indigo-200', 'bg-white');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-indigo-200', 'bg-white');
            });
        });

        // Simulation en temps réel
        function updateSimulation() {
            const montant = parseFloat(document.querySelector('input[name="montant"]').value) || 0;
            const taux = parseFloat(document.querySelector('input[name="taux_interet"]').value) || 0;
            
            if (montant > 0 && taux > 0) {
                const interets = montant * (taux / 100);
                const total = montant + interets;
                
                document.getElementById('sim-montant').textContent = montant.toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' {{ $compte->devise }}';
                
                document.getElementById('sim-interets').textContent = interets.toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' {{ $compte->devise }}';
                
                document.getElementById('sim-total').textContent = total.toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' {{ $compte->devise }}';
                
                document.getElementById('simulation').classList.remove('hidden');
            } else {
                document.getElementById('simulation').classList.add('hidden');
            }
        }

        // Écouter les changements
        document.querySelector('input[name="montant"]').addEventListener('input', updateSimulation);
        document.querySelector('input[name="taux_interet"]').addEventListener('input', updateSimulation);

        // Validation
        document.querySelector('input[name="montant"]').addEventListener('input', function() {
            const value = parseFloat(this.value) || 0;
            if (value < 0) {
                this.value = 0;
            }
        });

        document.querySelector('input[name="taux_interet"]').addEventListener('input', function() {
            const value = parseFloat(this.value) || 0;
            if (value < 0.1) {
                this.value = 0.1;
            }
            if (value > 50) {
                this.value = 50;
            }
        });

        document.querySelector('input[name="duree"]').addEventListener('input', function() {
            const value = parseInt(this.value) || 0;
            if (value < 1) {
                this.value = 1;
            }
            if (value > 120) {
                this.value = 120;
            }
        });
    </script>
</body>
</html>