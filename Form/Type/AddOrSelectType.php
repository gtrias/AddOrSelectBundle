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
use gtrias\AddOrSelectBundle\Form\Listener\DynamicFormListener;

/**
* DatetimeType
*
*/
class AddOrSelectType extends AbstractType
{
    private $configs;

	private $registry;

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
		$builder->addViewTransformer(
			new EntityDataTransformer( $this->registry, $options['class']), true
		);

		$builder->addEventSubscriber(new DynamicFormListener($builder->getFormFactory()));
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
