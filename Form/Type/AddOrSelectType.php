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
class AddOrSelectBundleType extends AbstractType
{

	public function getParent()
	{
		return 'entity';
	}


	public function getName()
	{
		return 'gtrias_addroselect';
	}
}
