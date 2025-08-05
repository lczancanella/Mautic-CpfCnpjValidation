<?php

namespace MauticPlugin\CpfCnpjValidationBundle\EventListener;

use Mautic\LeadBundle\Event\LeadEvent;
use Mautic\LeadBundle\LeadEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LeadSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            LeadEvents::LEAD_PRE_SAVE => ['onLeadPreSave', 0],
        ];
    }

    public function onLeadPreSave(LeadEvent $event): void
    {
        $lead = $event->getLead();

        if ($lead->hasField('cpf')) {
            $cpf = $lead->getFieldValue('cpf');
            if (!empty($cpf) && !$this->isValidCpf($cpf)) {
                throw new \InvalidArgumentException('O CPF informado é inválido.');
            }
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
