<?php

namespace gtrias\AddOrSelectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use gtrias\AddOrSelectBundle\Form\DataTransformer\EntityDataTransformer;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
* DatetimeType
*
*/
class AddOrSelectType extends EntityType
{
    private $configs;

	protected $registry;

    public function __construct(RegistryInterface $registry, array $configs = array())
    {
		$this->registry = $registry;
        $this->configs = $configs;
    }


	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//$builder->addModelTransformer(
			//new EntityDataTransformer( $this->registry, $options['class']), true
		//);

		$name = $builder->getName();

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options, $name) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();

                //$formModifier($event->getForm()->getParent(), $data, $options, $name);
            }
        );

		$em = $this->registry->getManager();

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($options, $name, $em) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $data = $event->getData();

				$entity = $em->getRepository($options['class'])->findOneBy(array('name' => $data));

				if(!$entity && is_numeric($data))
					$entity = $em->getRepository($options['class'])->findOneBy(array('id' => $data));

				if(!$entity){

					$entity = new $options['class']();
					$entity->setName($data);
					$em->persist($entity);
					$em->flush();

				}

				//TODO: Check this
				$event->setData($entity->getId());
                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                //$formModifier($event->getForm()->getParent(), $data, $options, $name);
            }
        );

	}

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['configs'] = $options['configs'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

		parent::setDefaultOptions($resolver);

        $defaults = $this->configs;
        $resolver
            ->setDefaults(array(
                'configs'       => $defaults,
                'transformer'   => null,
            ))
            ->setNormalizers(array(
                'configs' => function (Options $options, $configs) use ($defaults) {
                    return array_merge($defaults, $configs);
                },
            ))
        ;

		// ldd($resolver->offsetGet('choice_list'));

        //$resolver->setDefaults(array(
            //'choice_list' => $resolver->offsetGet('choice_list'),
        //));

    }


	public function getParent()
	{
		return 'entity';
	}


	public function getName()
	{
		return 'gtrias_addorselect';
	}
}
