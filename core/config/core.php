<?php
return [
    'app' => [
        'visible' => 1,
        'constants' => [
            'debug' => [
                'show_errors' => 0,
                'debug_mode' => 0,
            ],
        ],
        'version' => '1.2.1.10',
        'game_name' => [
            'visible' => 1,
            'title' => 'Game Title',
            'description' => "The title for your game",
            'datatype' => "text",
            'value' => 'ezRPG 1.2.1.10'
        ],
        'site_url' => [
            'visible' => 1,
            'title' => 'Site URL',
            'description' => "The url for your game",
            'datatype' => "text",
            'value' => 'http://ezrpgproject.net'
        ],
        'pass_encryption' => [
            'visible' => 1,
            'title' => 'Password Encryption',
            'description' => 'Determine the type of password encryption to use for User Logins.',
            'datatype' => 'select',
            'options' => [
                [
                    'name'  => 'legacy',
                    'title' => 'ezRPG Legacy',
                    'description' => 'ezRPG Legacy Encryption method',
                    'value' => 1
                ],
                [
                    'name'  => 'pbkdf2',
                    'title' => 'PBKDF2 Method',
                    'description' => 'PBKDF2',
                    'value' => 2
                ],
                [
                    'name'  => 'bcrypt',
                    'title' => 'BCRYPT',
                    'description' => 'BCRYPT',
                    'value' => 3
                ],
            ],
            'value' => 'legacy',
        ],
        'validation' => [
            'visible' => 1,
            'title' => 'Validation Settings',
            'description'   => 'Set the specifics for the ezRPG Validation',
            'children' => [
                'passLenMin' => [
                    'visible' => 1,
                    'title' => 'Password Minimum Length',
                    'description' => 'Set the minimum length for the password',
                    'datatype' => "text",
                    'value' => 6
                ],
                'passLenMax' => [
                    'visible' => 1,
                    'title' => 'Password Maximum Length (optional)',
                    'description' => 'Set the maximum length for the password',
                    'datatype' => "text",
                    'value' => 20
                ],
                'passLens' => [
                    'visible' => 1,
                    'title' => 'Password Lengths',
                    'description' => 'Determine how to test the lengths',
                    'datatype' => 'select',
                    'options' => [
                        'passMin' => [
                            'title' => 'Minimum Length',
                            'value' => 'min'
                        ],
                        'passMinMax' => [
                            'title' => 'Minimum & Maximum Length',
                            'description' => 'Check against both a Min and Max',
                            'value' => 'minmax'
                        ],
                    ]
                ],
            ],

        ],
        'default_module' => [
            'visible' => 1,
            'title' => 'Default Module',
            'description' => 'Choose the default module for your frontpage',
            'value' => 'Index',
            'datatype' => 'text'
        ],
    ],
];