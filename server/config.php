<?php

$config = [

    'auth' => [ //the Users
        [
            "username" => "admin@admin.com",
            "password" => "12345678"
        ],
        [
            "username" => "admin2@admin.com",
            "password" => "12345678"
        ]
    ],
    'db' => [ //the db config
        'preprod' => [
            'host' => 'preprod.db.com',
            'database' => 'preprod',
            'username' => 'root',
            'password' => ''
        ],
        'prod' => [
            'host' => 'prod.db.com',
            'database' => 'prod',
            'username' => 'root',
            'password' => ''
        ],
    ],
    'ssh' => [ //the ssh config if needed
            'preprod' => null,
            'prod' => [
                'host' => 'sshhost',
                'username' => 'ssshuser'
            ]
    ] ,
    'scripts' => [  //the scripts
            [
                "nom" => "Remove the character '&#xA0;' (No-Break Space) from the XML field of a record in the Postgres Database",
                "description" => "the character '&#xA0;' is making problems when exporting records to files...",
                "chemin" => "remove_no_break_space",
                "type" => "sql",
                "parametres" => [
                    [
                        "key"=> "record_reference",
                        "controlType"=> "textbox",
                        "type" => "text",
                        "label" => "Record reference",
                        "order" => 1
                    ]
                ]
            ]
            ,
            [
                "nom" => "Execute the import job again",
                "description" => "When the import job crash for unknown reasons execute this script to restart it",
                "chemin" => "relaunch_import_job",
                "type" => "bash",
                "parametres" => [
                    [
                        "key"=> "directory",
                        "controlType"=> "textbox",
                        "type" => "text",
                        "label" => "Directory",
                        "order" => 1
                    ],
                    [
                        "key"=> "number_attempts",
                        "controlType"=> "textbox",
                        "type" => "text",
                        "label" => "Numer of attempts",
                        "order" => 2
                    ]
                ]
            ]
    ]
];