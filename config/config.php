<?php

return [
    'name'        => 'CpfCnpjValidation',
    'description' => 'Valida campos de CPF (contatos) e CNPJ (empresas).',
    'version'     => '1.0',
    'author'      => 'Luiz Carlos Zancanella Junior',
    'services'    => [
        'events' => [
            'cpfcnpjvalidation.lead.subscriber' => [
                'class' => \MauticPlugin\CpfCnpjValidationBundle\EventListener\LeadSubscriber::class,
                'tags'  => [['name' => 'kernel.event_subscriber']],
            ],
            'cpfcnpjvalidation.company.subscriber' => [
                'class' => \MauticPlugin\CpfCnpjValidationBundle\EventListener\CompanySubscriber::class,
                'tags'  => [['name' => 'kernel.event_subscriber']],
            ],
        ],
    ],
];
