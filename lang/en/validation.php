<?php

return [
    'between' => [
        'array' => 'The :attribute must have between :min and :max items.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'numeric' => 'The :attribute must be between :min and :max.',
        'string' => 'The :attribute must be between :min and :max characters.',
    ],
    'date' => 'The :attribute is not a valid date.',
    'image' => 'The :attribute must be an image.',
    'email' => 'The :attribute must be a valid email address.',
    'password' => [
        'letters' => 'The :attribute must contain at least one letter.',
        'mixed' => 'The :attribute must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute must contain at least one number.',
        'symbols' => 'The :attribute must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'phone' => 'The :attribute field contains an invalid number.',
    'required' => 'The :attribute field is required.',
    'size' => [
        'array' => 'The :attribute must contain :size items.',
        'file' => 'The :attribute must be :size kilobytes.',
        'numeric' => 'The :attribute must be :size.',
        'string' => 'The :attribute must be :size characters.',
    ],
    'string' => 'The :attribute must be a string.',
    'integer' => 'The :attribute must be an integer.',
    'unique' => 'The :attribute has already been taken.',

    'distinct' => 'The :attribute field has a duplicate value.',

    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',

    'custom' => [
        'address' => [
            'geocode' => 'Failed to find coordinates based on provided address.',
        ],
        'fallback_locale' => [
            'incomplete' => 'Not all content has been translated in this language.',
        ],
    ],

    'attributes' => [
        'firstname' => 'Firstname',
        'lastname' => 'Lastname',
        'name' => 'Name',
        'birth_date' => 'Date of birth',
        'email' => 'E-mail address',
        'password' => 'Password',
        'passwordRepeat' => 'Repeat password',
        'phone' => 'Phone number',
        'street' => 'Street name',
        'number' => 'House number',
        'box_number' => 'Box number',
        'zipcode' => 'Zipcode',
        'city' => 'City',
        'image' => 'Image',
        'sex' => 'Sex',
        'race' => 'Race',
        'description' => 'Description',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'tiktok' => 'Tik Tok',
        'date' => 'Date',
        'openingPeriods.*.open' => 'Opening hour',
        'openingPeriods.*.close' => 'Closing hour',
        'start_time' => 'Start time',
        'end_time' => 'End time',
    ],
    'same' => 'The :attribute and :other must match.',

    'max' => [
        'string' => 'The :attribute must not be greater than :max characters.',
    ],

    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],

    'regex' => 'The :attribute format is invalid.',
    'required_with' => 'The :attribute field is required when :values is present.',
];
