<?php

return [

    'types' => [
        'name' => [
            'type'  => 'text',
            'rules' => 'required|max:20',
        ],
        'phone' => [
            'type'  => 'tel',
            'rules' => 'required|max:15',
        ],
        'address' => [
            'type'  => 'text',
            'rules' => 'required|max:50',
        ],
        'email' => [
            'type'  => 'email',
            'rules' => 'required|max:100',
        ],
        'submit' => [
            'type' => 'submit',
        ],
    ],

];
