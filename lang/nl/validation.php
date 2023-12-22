<?php

return [
    'image' => 'Het :attribute moet een afbeelding zijn.',
    'email' => 'Het :attribute moet een geldig e-mailadres zijn.',
    'password' => [
        'letters' => 'Het :attribute moet minimaal één letter bevatten.',
        'mixed' => 'Het :attribute moet minimaal één hoofdletter en één kleine letter bevatten.',
        'numbers' => 'Het :attribute moet minstens één getal bevatten.',
        'symbols' => 'Het :attribute moet minstens één symbool bevatten.',
        'uncompromised' => 'Het opgegeven :attribuut is verschenen in een datalek. Kies een ander :attribuut.',
    ],
    'phone' => 'Het :attribute-veld bevat een ongeldig nummer.',
    'required' => 'Dit veld is verplicht.',
    'size' => [
        'array' => 'Het :attribute moet :size items bevatten.',
        'file' => 'Het :attribute moet :size kilobytes zijn.',
        'numeric' => 'Het :attribute moet :size zijn.',
        'string' => 'Het :attribute moet :size tekens zijn.',
    ],
    'string' => 'Het :attribute moet een string zijn.',
    'unique' => 'Het :attribute is al in gebruik.',

    'custom' => [
        'address' => [
            'geocode' => 'Kan geen coördinaten vinden op basis van het opgegeven adres.',
        ],
        'fallback_locale' => [
            'incomplete' => 'Niet alle inhoud is in deze taal vertaald.',
        ],
    ],

    'attributes' => [
        'name' => 'naam',
        'email' => 'email-adres',
        'phone' => 'telefoonnummer',
        'street' => 'straatnaam',
        'number' => 'huisnummer',
        'box_number' => 'toevoeging',
        'zipcode' => 'postcode',
        'city' => 'stad',
    ],
];
