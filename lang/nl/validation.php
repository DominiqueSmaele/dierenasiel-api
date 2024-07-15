<?php

return [
    'between' => [
        'array' => ':attribute moet tussen :min en :max liggen.',
        'file' => ':attribute moet tussen :min en :max kilobytes liggen.',
        'numeric' => ':attribute moet tussen :min en :max liggen.',
        'string' => ':attribute moet tussen :min en :max tekens liggen.',
    ],
    'date' => ':attribute moet een geldige datum zijn.',
    'image' => ':attribute moet een afbeelding zijn.',
    'email' => ':attribute moet een geldig e-mailadres zijn.',
    'password' => [
        'letters' => 'Het :attribute moet minimaal één letter bevatten.',
        'mixed' => 'Het :attribute moet minimaal één hoofdletter en één kleine letter bevatten.',
        'numbers' => 'Het :attribute moet minstens één getal bevatten.',
        'symbols' => 'Het :attribute moet minstens één symbool bevatten.',
        'uncompromised' => 'Het opgegeven :attribuut is verschenen in een datalek. Kies een ander :attribuut.',
    ],
    'phone' => ':attribute-veld moet een geldig nummer zijn.',
    'required' => 'Dit veld is verplicht.',
    'size' => [
        'array' => ':attribute moet :size items bevatten.',
        'file' => ':attribute moet :size kilobytes zijn.',
        'numeric' => ':attribute moet :size zijn.',
        'string' => ':attribute moet :size tekens zijn.',
    ],
    'string' => ':attribute moet een string zijn.',
    'integer' => ':attribute moet een nummer zijn.',
    'unique' => ':attribute is al in gebruik.',

    'distinct' => ':attribute bevat een duplicaat.',

    'before' => ':attribute moet een datum zijn voor :date.',
    'before_or_equal' => ':attribute moet een datum zijn voor of gelijk aan :date.',
    'after' => ':attribute moet een datum zijn na :date.',
    'after_or_equal' => ':attribute moet een datum zijn na of gelijk aan :date.',

    'custom' => [
        'address' => [
            'geocode' => 'Kan geen coördinaten vinden op basis van het opgegeven adres.',
        ],
        'fallback_locale' => [
            'incomplete' => 'Niet alle inhoud is in deze taal vertaald.',
        ],
    ],

    'attributes' => [
        'firstname' => 'Voornaam',
        'lastname' => 'Achternaam',
        'name' => 'Naam',
        'birth_date' => 'Geboortedatum',
        'email' => 'Email-adres',
        'password' => 'Wachtwoord',
        'passwordRepeat' => 'Herhaling Wachtwoord',
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
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'tiktok' => 'Tik Tok',
        'date' => 'Datum',
        'openingPeriods.*.open' => 'Openingsuur',
        'openingPeriods.*.close' => 'Sluitingsuur',
        'start_time' => 'Beginuur',
        'end_time' => 'Einduur',
    ],
    'same' => ':attribute komt niet overeen met :other.',

    'max' => [
        'string' => ':attribute mag maximum :max karakters bevatten.',
    ],

    'min' => [
        'string' => ':attribute moet minimum :min karakters bevatten.',
    ],

    'regex' => ':attribute moet minimum één cijfer bevatten.',
    'required_with' => ':attribute is verplicht als :values aanwezig is.',
];
