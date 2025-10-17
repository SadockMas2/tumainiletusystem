<x-filament::card class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">Membres du groupe : {{ $record->nom_groupe }}</h2>
        <x-filament::button color="success" size="lg" wire:click="$emit('openAddMemberModal', {{ $record->id }})">
            Ajouter un membre
        </x-filament::button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 divide-y divide-gray-300 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-10 py-6 text-left text-gray-700 font-semibold text-lg uppercase">Nom</th>
                    <th class="px-10 py-6 text-left text-gray-700 font-semibold text-lg uppercase">Post-nom</th>
                    <th class="px-10 py-6 text-left text-gray-700 font-semibold text-lg uppercase">Prénom</th>
                    <th class="px-10 py-6 text-left text-gray-700 font-semibold text-lg uppercase">Adresse</th>
                    <th class="px-10 py-6 text-left text-gray-700 font-semibold text-lg uppercase">Téléphone</th>
                    {{-- <th class="px-10 py-6 text-left text-gray-700 font-semibold text-lg uppercase">Email</th> --}}
                    <th class="px-10 py-6 text-center text-gray-700 font-semibold text-lg uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($membres as $membre)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-10 py-6 text-lg leading-relaxed">{{ $membre->nom }}</td>
                    <td class="px-10 py-6 text-lg leading-relaxed">{{ $membre->postnom }}</td>
                    <td class="px-10 py-6 text-lg leading-relaxed">{{ $membre->prenom }}</td>
                    <td class="px-10 py-6 text-lg leading-relaxed">{{ $membre->adresse }}</td>
                    <td class="px-10 py-6 text-lg leading-relaxed">{{ $membre->telephone }}</td>
                    {{-- <td class="px-10 py-6 text-lg leading-relaxed">{{ $membre->email }}</td> --}}
                    <td class="px-10 py-6 text-center">
                        <x-filament::button color="danger" size="md" wire:click="removeMember({{ $membre->id }}, {{ $record->id }})">
                            Supprimer
                        </x-filament::button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::card>
