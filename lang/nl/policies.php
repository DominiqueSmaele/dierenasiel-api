<?php

return [
    'admin_dashboard' => [
        'user' => [
            'view_any_developer' => [
                'no_permission' => 'U heeft geen toestemming om ontwikkelaars te bekijken.',
            ],
        ],
        'shelter' => [
            'view_any' => [
                'no_permission' => 'U heeft geen toestemming om dierenasielen te bekijken.',
            ],
            'create' => [
                'no_permission' => 'U heeft geen toestemming om dierenasielen aan te maken.',
            ],
            'update' => [
                'no_permission' => 'U heeft geen toestemming om dierenasielen aan te passen.',
            ],
            'delete' => [
                'no_permission' => 'U heeft geen toestemming om dierenasielen te verwijderen.',
                'deleted' => 'U kan geen dierenasiel verwijderen dat al verwijderd is.',
            ],
        ],
        'animal' => [
            'view_any' => [
                'no_permission' => 'U heeft geen toestemming om dieren te bekijken.',
            ],
            'create' => [
                'no_permission' => 'U heeft geen toestemming om dieren aan te maken.',
            ],
            'update' => [
                'no_permission' => 'U heeft geen toestemming om dieren aan te passen.',
            ],
            'delete' => [
                'no_permission' => 'U heeft geen toestemming om dieren te verwijderen.',
                'deleted' => 'Dit dier is al verwijderd geweest.',
                'shelter_deleted' => 'U kan geen dier verwijderen waarvan het dierenasiel al verwijderd is.',
            ],
        ],
    ],
];
