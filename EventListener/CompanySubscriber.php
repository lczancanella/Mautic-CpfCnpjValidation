<?php

namespace MauticPlugin\CpfCnpjValidationBundle\EventListener;

use Mautic\LeadBundle\LeadEvents;
use Mautic\LeadBundle\Event\CompanyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CompanySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            LeadEvents::COMPANY_PRE_SAVE => ['onCompanyPreSave', 0],
        ];
    }

    public function onCompanyPreSave(CompanyEvent $event): void
    {
        $company = $event->getCompany();
        $cnpj = $company->getFieldValue('cnpj');

        if ($cnpj && !$this->validaCNPJ($cnpj)) {
            throw new \Exception("CNPJ inválido: $cnpj");
        }
    }

    private function validaCNPJ(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj)) return false;

        $t1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($s = 0, $i = 0; $i < 12; $i++) $s += $cnpj[$i] * $t1[$i];
        $r = $s % 11;
        if ($cnpj[12] != ($r < 2 ? 0 : 11 - $r)) return false;

        $t2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($s = 0, $i = 0; $i < 13; $i++) $s += $cnpj[$i] * $t2[$i];
        $r = $s % 11;
        if ($cnpj[13] != ($r < 2 ? 0 : 11 - $r)) return false;

        return true;
    }
}
