<?php

namespace MauticPlugin\CpfCnpjValidationBundle\EventListener;

use Mautic\FormBundle\Event\FormEvents;
use Mautic\FormBundle\Event\ValidationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CompanySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::VALIDATE_FORM => ['onValidate', 0],
        ];
    }

    public function onValidate(ValidationEvent $event): void
    {
        $data = $event->getData();

        // Verifica se existe o campo personalizado "cnpj"
        if (isset($data['cnpj']) && !$this->isValidCnpj($data['cnpj'])) {
            $event->addError('cnpj', 'O CNPJ informado é inválido.');
        }
    }

    private function isValidCnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        for ($t = 12; $t < 14; $t++) {
            $d = 0;
            $c = 0;
            for ($m = $t - 7, $i = 0; $i < $t; $i++) {
                $d += $cnpj[$i] * $m--;
                if ($m < 2) $m = 9;
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$t] != $d) return false;
        }

        return true;
    }
}
