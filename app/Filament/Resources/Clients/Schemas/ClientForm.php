<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')->required(),
                TextInput::make('postnom')->required(),
                TextInput::make('prenom')->required(),
                DatePicker::make('date_naissance'),
                TextInput::make('email')->label('Email address')->email()->required(),
                TextInput::make('telephone')->tel(),
                TextInput::make('adresse'),
                TextInput::make('ville'),
                Select::make('pays')
                    ->label('Pays')
                    ->options([
                        'AF' => 'Afghanistan',
                        'AL' => 'Albanie',
                        'DZ' => 'Algérie',
                        'AO' => 'Angola',
                        'AR' => 'Argentine',
                        'AM' => 'Arménie',
                        'AU' => 'Australie',
                        'AT' => 'Autriche',
                        'AZ' => 'Azerbaïdjan',
                        'BE' => 'Belgique',
                        'BJ' => 'Bénin',
                        'BF' => 'Burkina Faso',
                        'BI' => 'Burundi',
                        'BR' => 'Brésil',
                        'CA' => 'Canada',
                        'CF' => 'Centrafrique',
                        'TD' => 'Tchad',
                        'CL' => 'Chili',
                        'CN' => 'Chine',
                        'CD' => 'République Démocratique du Congo',
                        'CG' => 'Congo',
                        'CI' => "Côte d'Ivoire",
                        'DK' => 'Danemark',
                        'DJ' => 'Djibouti',
                        'EG' => 'Égypte',
                        'ET' => 'Éthiopie',
                        'FR' => 'France',
                        'DE' => 'Allemagne',
                        'GA' => 'Gabon',
                        'GH' => 'Ghana',
                        'GR' => 'Grèce',
                        'IN' => 'Inde',
                        'IT' => 'Italie',
                        'JP' => 'Japon',
                        'KE' => 'Kenya',
                        'MG' => 'Madagascar',
                        'ML' => 'Mali',
                        'MA' => 'Maroc',
                        'MU' => 'Maurice',
                        'NE' => 'Niger',
                        'NG' => 'Nigeria',
                        'RW' => 'Rwanda',
                        'SN' => 'Sénégal',
                        'ZA' => 'Afrique du Sud',
                        'ES' => 'Espagne',
                        'SD' => 'Soudan',
                        'SE' => 'Suède',
                        'CH' => 'Suisse',
                        'TZ' => 'Tanzanie',
                        'TG' => 'Togo',
                        'TN' => 'Tunisie',
                        'UG' => 'Ouganda',
                        'GB' => 'Royaume-Uni',
                        'US' => 'États-Unis',
                        'ZM' => 'Zambie',
                        'ZW' => 'Zimbabwe',
                    ])
                    ->searchable()
                    ->required(),
                TextInput::make('code_postal'),
                Hidden::make('id_createur')->default(Filament::auth()->id()),
                TextInput::make('status')->required()->default('actif'),
                TextInput::make('identifiant_national'),
                Select::make('type_client')
                    ->label('Type client')
                    ->options([
                        'Prt' => 'Particulier',
                        'Etrp'=> 'Entreprise',
                    ])
                    ->searchable()
                    ->required(),
            ]);
    }
}
