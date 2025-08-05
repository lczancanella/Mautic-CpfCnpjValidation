<?php

return [
    'name'        => 'CpfCnpjValidation',
    'description' => 'Valida campos de CPF (contatos) e CNPJ (empresas). v1.0.1',
    'version'     => '1.0.1',
    'author'      => 'Luiz Carlos Zancanella Junior',
    'services'    => [
        'events' => [
            'cpfcnpjvalidation.lead.subscriber' => [
                'class' => \MauticPlugin\CpfCnpjValidationBundle\EventListener\LeadSubscriber::class,
                'tags'  => ['kernel.event_subscriber'],
            ],
            'cpfcnpjvalidation.company.subscriber' => [
                'class' => \MauticPlugin\CpfCnpjValidationBundle\EventListener\CompanySubscriber::class,
                'tags'  => ['kernel.event_subscriber'],
            ],
        ],
    ],
];
