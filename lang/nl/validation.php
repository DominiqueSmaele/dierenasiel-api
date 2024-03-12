<?php

return [
    'image' => ':attribute moet een afbeelding zijn.',
    'email' => ':attribute moet een geldig e-mailadres zijn.',
    'password' => [
        'letters' => 'Het :attribute moet minimaal één letter bevatten.',
        'mixed' => 'Het :attribute moet minimaal één hoofdletter en één kleine letter bevatten.',
        'numbers' => 'Het :attribute moet minstens één getal bevatten.',
        'symbols' => 'Het :attribute moet minstens één symbool bevatten.',
        'uncompromised' => 'Het opgegeven :attribuut is verschenen in een datalek. Kies een ander :attribuut.',
    ],
    'phone' => ':attribute-veld bevat een ongeldig nummer.',
    'required' => 'Dit veld is verplicht.',
    'size' => [
        'array' => ':attribute moet :size items bevatten.',
        'file' => ':attribute moet :size kilobytes zijn.',
        'numeric' => ':attribute moet :size zijn.',
        'string' => ':attribute moet :size tekens zijn.',
    ],
    'string' => ':attribute moet een string zijn.',
    'unique' => ':attribute is al in gebruik.',
    'before_or_equal' => 'Datum moet voor of gelijk zijn aan vandaag.',

    'custom' => [
        'address' => [
            'geocode' => 'Kan geen coördinaten vinden op basis van het opgegeven adres.',
        ],
        'fallback_locale' => [
            'incomplete' => 'Niet alle inhoud is in deze taal vertaald.',
        ],
    ],

    'attributes' => [
        'name' => 'Naam',
        'email' => 'Email-adres',
        'password' => 'Wachtwoord',
        'passwordRepeat' => 'Wachtwoord',
        'phone' => 'Telefoonnummer',
        'street' => 'Straatnaam',
        'number' => 'Huisnummer',
        'box_number' => 'Toevoeging',
        'zipcode' => 'Postcode',
        'city' => 'Stad',
        'image' => 'Foto',
        'sex' => 'Geslacht',
        'race' => 'Ras',
        'description' => 'Omschrijving',
    ],
    'same' => ':attribute komt niet overeen.',

    'max' => [
        'string' => ':attribute mag maximum :max karakters bevatten.',
    ],

    'min' => [
        'string' => ':attribute moet minimum :min karakters bevatten.',
    ],

    'regex' => ':attribute moet minimum één cijfer bevatten.',
];
