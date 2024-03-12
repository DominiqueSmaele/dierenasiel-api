<?php

return [
    'admin_dashboard' => [
        'user' => [
            'view_any_developer' => [
                'no_permission' => 'U heeft geen toestemming om ontwikkelaars te bekijken.',
            ],
            'view_any_admin' => [
                'no_permission' => 'U heeft geen toestemming om beheerders te bekijken.',
            ],
            'create_admin' => [
                'no_permission' => 'U heeft geen toestemming om beheerders aan te maken.',
            ],
            'update_admin' => [
                'no_permission' => 'U heeft geen toestemming om beheerders aan te passen.',
                'incorrect_role' => 'De gebruiker die u probeert aan te passen is geen beheerder.',
            ],
            'delete_admin' => [
                'no_permission' => 'U heeft geen toestemming om beheerders te verwijderen.',
                'incorrect_role' => 'De gebruiker die u probeert te verwijderen is geen beheerder.',
                'no_self_delete' => 'Het is niet mogelijk om uzelf te verwijderen als beheerder.',
            ],
        ],
        'shelter' => [
            'view_any' => [
                'no_permission' => 'U heeft geen toestemming om dierenasielen te bekijken.',
            ],
            'view' => [
                'no_permission' => 'U heeft geen toestemming om dit dierenasiel te bekijken.',
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
            'view' => [
                'no_permission' => 'U heeft geen toestemming om dit dier te bekijken.',
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
            'update_qualities' => [
                'no_permission' => 'U heeft geen toestemming om de eigenschappen van dit dier aan te passen.',
            ],
        ],
        'quality' => [
            'view_any' => [
                'no_permission' => 'U heeft geen toestemming om eigenschappen te bekijken',
            ],
            'create' => [
                'no_permission' => 'U heeft geen toestemming om eigenschappen aan te maken.',
            ],
            'update' => [
                'no_permission' => 'U heeft geen toestemming om eigenschappen aan te passen.',
            ],
            'delete' => [
                'no_permission' => 'U heeft geen toestemming om eigenschappen te verwijderen.',
            ],
        ],
        'opening_period' => [
            'view' => [
                'no_permission' => 'U heeft geen toestemming om de openingsuren te bekijken.',
            ],
            'create' => [
                'no_permission' => 'U heeft geen toestemming om openingsuren aan te maken.',
            ],
            'update' => [
                'no_permission' => 'U heeft geen toestemming om openingsuren aan te passen.',
            ],
        ],
    ],
];
