<?php

namespace gtrias\AddOrSelectBundle\Form\Listener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;

class DynamicFormListener implements EventSubscriberInterface
{
    private $factory;

    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT   => 'preSubmit',
            FormEvents::PRE_SET_DATA => 'preSetData',
        );
    }
    public function preSetData(FormEvent $event)
    {
        // Don't need
        return;
    }
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        if (!$data) return; // If nothing was actually chosen

        $form = $event->getForm();

        /* =================================================
         * All we need to do is to replace the choice with one containing the $gender value
         * Once this is done $form->isValid() will pass
         *
         * I did attempt to just add the option to the existing gender choice 
         * but could not see how to do it.  
         * $genderForm = form->get('gender'); // Returns a Form object
         * $genderForm->addNewOptionToChoicesList ???
         * 
         * Might want to look up 'whatever' but that only comes into play
         * if the form fails validation and you paas it back to the user
         * You could also use client side javascript to replace 'whatever' with the correct value
         */
		$config = $form->getConfig();
		$type = $config->getType()->getName();
		$options = $config->getOptions();
//ldd($type);
        $form->getParent()->add('levels', $type, array_replace($options, array(
			'choices' => array($data => $data)
		)));
        return;
    }
}
