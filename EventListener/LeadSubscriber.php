<?php

namespace MauticPlugin\CpfCnpjValidationBundle\EventListener;

use Mautic\FormBundle\Event\FormEvents;
use Mautic\FormBundle\Event\ValidationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LeadSubscriber implements EventSubscriberInterface
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

        // Verifica se existe o campo personalizado "cpf"
        if (isset($data['cpf']) && !$this->isValidCpf($data['cpf'])) {
            $event->addError('cpf', 'O CPF informado é inválido.');
        }
    }

    private function isValidCpf(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
