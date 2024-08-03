<?php

return [
    'admin_dashboard' => [
        'user' => [
            'view_any_developer' => [
                'no_permission' => 'You don\'t have permission to view developers.',
            ],
            'view_any_admin' => [
                'no_permission' => 'You don\'t have permission to view admins.',
            ],
            'create_admin' => [
                'no_permission' => 'You don\'t have permission to create admins.',
            ],
            'update_admin' => [
                'no_permission' => 'You don\'t have permission to update admins.',
                'incorrect_role' => 'The user you\'re trying to update is not an admin.',
            ],
            'delete_admin' => [
                'no_permission' => 'You don\'t have permission to delete admins.',
                'incorrect_role' => 'The user you\'re trying to delete is not an admin.',
                'no_self_delete' => 'It\'s not possible to delete yourself as an admin.',
            ],
        ],
        'shelter' => [
            'view_any' => [
                'no_permission' => 'You don\'t have permission to view shelters.',
            ],
            'view' => [
                'no_permission' => 'You don\'t have permission to view this shelter.',
            ],
            'create' => [
                'no_permission' => 'You don\'t have permission to create shelters.',
            ],
            'update' => [
                'no_permission' => 'You don\'t have permission to update shelters.',
            ],
            'delete' => [
                'no_permission' => 'You don\'t have permission to delete shelters.',
                'deleted' => 'You can\'t delete a shelter that has already been deleted.',
            ],
        ],
        'animal' => [
            'view_any' => [
                'no_permission' => 'You don\'t have permission to view animals.',
            ],
            'view' => [
                'no_permission' => 'You don\'t have permission to view this animal.',
            ],
            'create' => [
                'no_permission' => 'You don\'t have permission to create animals.',
            ],
            'update' => [
                'no_permission' => 'You don\'t have permission to update animals.',
            ],
            'delete' => [
                'no_permission' => 'You don\'t have permission to delete animals.',
                'deleted' => 'This animal has already been deleted.',
                'shelter_deleted' => 'You can\'t delete an animal which shelter has already been deleted.',
            ],
            'update_qualities' => [
                'no_permission' => 'You don\'t have permission to update the qualities of this animal.',
            ],
        ],
        'quality' => [
            'view_any' => [
                'no_permission' => 'You don\'t have permission to view qualities.',
            ],
            'create' => [
                'no_permission' => 'You don\'t have permission to create qualities.',
            ],
            'update' => [
                'no_permission' => 'You don\'t have permission to update qualities.',
            ],
            'delete' => [
                'no_permission' => 'You don\'t have permission to delete qualities.',
            ],
        ],
        'opening_period' => [
            'view' => [
                'no_permission' => 'You don\'t have permission to view opening hours.',
            ],
            'create' => [
                'no_permission' => 'You don\'t have permission to create opening hours.',
            ],
            'update' => [
                'no_permission' => 'You don\'t have permission to update opening hours.',
            ],
        ],
        'timeslot' => [
            'view_any' => [
                'no_permission' => 'You don\'t have permission to view the volunteer agenda.',
            ],
            'create' => [
                'no_permission' => 'You don\'t have permission to create timeslots.',
            ],
            'update' => [
                'no_permission' => 'You don\'t have permission to update timeslots.',
            ],
            'delete' => [
                'no_permission' => 'You don\'t have permission to delete timeslots.',
                'shelter_deleted' => 'You can\'t delete a timeslot which shelter has already been deleted.',
            ],
            'delete_volunteer' => [
                'no_permission' => 'You don\'t have permission to delete volunteers from timeslots.',
                'shelter_deleted' => 'You can\'t delete volunteers from timeslots which shelter has already been deleted.',
            ],
        ],
    ],
];
