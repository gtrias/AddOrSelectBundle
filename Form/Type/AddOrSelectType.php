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
use Symfony\Component\Form\FormEvents;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use gtrias\AddOrSelectBundle\Form\EventListener\AddOrSelectFieldSubscriber;

use gtrias\AddOrSelectBundle\Form\DataMapper\AddOrSelectDataMapper;

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
		/*$builder->addEventListener(FormEvents::POST_SUBMIT, function ($event) {
			$event->stopPropagation();
		}, 900); // Always set a higher priority than ValidationListener*/

		$em = $this->registry->getManager();

        $builder->addEventSubscriber( new AddOrSelectFieldSubscriber($options, $options['base_property'], $em) );
		// El transformer hace que no funcione la validacion del formulario, con esto desactivado se puede validar haciendo refresh en el primer error
		//$builder->addViewTransformer(new EntityDataTransformer($this->registry, $options['class'], $options['base_property']));
		//

		//$builder->setDataMapper(new AddOrSelectDataMapper($em));

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
				'base_property' => 'name',
                //'transformer'   => null,
				'csrf_protection' => false,
				'validation-groups' => false,
            ))
            ->setNormalizers(array(
                'configs' => function (Options $options, $configs) use ($defaults) {
                    return array_merge($defaults, $configs);
                },
            ))
        ;

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
