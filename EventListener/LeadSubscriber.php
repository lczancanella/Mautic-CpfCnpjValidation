<?php

namespace MauticPlugin\CpfCnpjValidationBundle\EventListener;

use Mautic\LeadBundle\Event\LeadEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LeadSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'mautic.lead_pre_save' => ['onLeadPreSave', 0],
        ];
    }

    public function onLeadPreSave(LeadEvent $event)
    {
        $lead = $event->getLead();

        $cpf = $lead->getFieldValue('cpf');
        $cnpj = $lead->getFieldValue('cnpj');

        if ($cpf && !$this->isValidCPF($cpf)) {
            throw new \RuntimeException('CPF inválido.');
        }

        if ($cnpj && !$this->isValidCNPJ($cnpj)) {
            throw new \RuntimeException('CNPJ inválido.');
        }
    }

    private function isValidCPF(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }
        return true;
    }

    private function isValidCNPJ(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj)) return false;

        for ($t = 12; $t < 14; $t++) {
            for ($d = 0, $p = 0, $c = $t - 7; $p < $t; $p++) {
                $d += $cnpj[$p] * $c--;
                if ($c < 2) $c = 9;
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$p] != $d) return false;
        }
        return true;
    }
}