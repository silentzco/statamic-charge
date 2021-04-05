<?php

return [
    /*
     * When a user subscribes to a plan, which role should they be given?
     */
    'roles_and_plans' => [
        [
            'plan' => '',
            'role' => '',
        ],
    ],

    // send email address for all Charge emails
    'email' => [
        'sender' => '',
    ]

    /*
     * For all below, which template & subject to use for which email.
     * Set to null to not send that email
     */
    'one_time_payment_template' => '',
    'one_time_payment_subject' => '',

    'subscription_created_template' => '',
    'subscription_created_subject' => '',
    'subscription_updated_template' => '',
    'subscription_updated_subject' => '',
    'subscription_canceled_template' => '',
    'subscription_canceled_subject' => '',
    'subscription_upcoming_payment_template' => '',
    'subscription_upcoming_payment_subject' => '',
    'subscription_payment_succeeded_template' => '',
    'subscription_payment_succeeded_subject' => '',
    'subscription_payment_failed_template' => '',
    'subscription_payment_failed_subject' => '',
    'customer_updated_template' => '',
    'customer_updated_subject' => '',
];
