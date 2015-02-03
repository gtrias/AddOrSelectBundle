<?php

namespace gtrias\AddOrSelectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType as BaseDateType;

/**
* DatetimeType
*
*/
class AddOrSelectType extends AbstractType
{
    private $configs;

    public function __construct(array $configs = array())
    {
        $this->configs = $configs;
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
