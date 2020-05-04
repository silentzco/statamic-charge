<?php

return [
    'subscription' => [
        'roles' => [
            [
                'plan' => '',
                'role' => '',
            ],
        ],
    ],
    'email' => [
        'from' => '',
        'one-time' => [
            'payment_template' => '',
            'payment_subject' => '',
        ],
        'subscription' => [
            'created_template' => '',
            'created_subject' => '',
            'updated_template' => '',
            'updated_subject' => '',
            'canceled_template' => '',
            'canceled_subject' => '',
            'upcoming_payment_template' => '',
            'upcoming_payment_subject' => '',
            'payment_succeeded_template' => '',
            'payment_succeeded_subject' => '',
            'payment_failed_template' => '',
            'payment_failed_subject' => '',
        ],
        'customer' => [
            'updated_template' => '',
            'updated_subject' => '',
        ],
    ],
];
