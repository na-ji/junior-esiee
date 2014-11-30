<?php

namespace JuniorEsiee\FinancesBundle\Form\EventListener;

use JuniorEsiee\FinancesBundle\Entity\CustomerInvoice;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddPaidAtSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        /** @var CustomerInvoice $invoice */
        $invoice = $event->getData();
        $form = $event->getForm();

        if ($invoice->getPaid()) {
            $form->add('paidAt', 'datepicker', array(
                'label' => 'Date de paiment'
            ));
        }
    }
}