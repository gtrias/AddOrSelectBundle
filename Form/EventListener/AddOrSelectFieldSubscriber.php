<?php
namespace gtrias\AddOrSelectBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddOrSelectFieldSubscriber implements EventSubscriberInterface
{
	private $options;
	private $name;
	private $em;

	public function __construct($options, $name, $em)
	{
		$this->options = $options;
		$this->name = $name;
		$this->em = $em;
	}

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
		return array(
			FormEvents::PRE_SUBMIT => 'preSubmit',
			FormEvents::PRE_SET_DATA => 'preSetData',
			FormEvents::POST_SET_DATA => 'postSetData'
		);
    }

    public function preSubmit(FormEvent $event)
    {

		$form = $event->getForm();
		$data = $event->getData();

		if($data === null)
			return;

		$em = $this->em;
		$options = $this->options;

		// We check if the entity field is going together with other fields
		//$related_to_persist = $this->getRelatedFields($event);

		if($data instanceof $options['class'])
			$entity = $data;

		$entity = $em->getRepository($options['class'])->findOneBy(array('id' => $data));

		if(!$entity && !is_numeric($data))
			$entity = $em->getRepository($options['class'])->findOneBy(array('name' => $data));

		if(!$entity){
			$entity = new $options['class']();
			$method = 'set'.$options['base_property'];
			$entity->$method($data);

			$em->persist($entity);
			$em->flush();
		}

		//ldd($event);
		if($entity){
			$event->setData($entity->getId());
			//$event->getForm()->setData($entity->getId());
		}

    }

	public function preSetData(FormEvent $event)
	{
	}

	public function postSetData(FormEvent $event)
	{
	}
}

