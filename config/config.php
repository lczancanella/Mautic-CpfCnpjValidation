<?php

return [
    'name'        => 'CpfCnpjValidation',
    'description' => 'Valida campos personalizados de CPF e CNPJ no lead.',
    'version'     => '1.0',
    'author'      => 'Luiz Carlos Zancanella Junior',
    'services'    => [
        'events' => [
            'cpfcnpjvalidation.lead.subscriber' => [
                'class' => \MauticPlugin\CpfCnpjValidationBundle\EventListener\LeadSubscriber::class,
                'tags'  => [['name' => 'kernel.event_subscriber']],
            ],
        ],
    ],
];