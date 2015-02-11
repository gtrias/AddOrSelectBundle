<?php

namespace gtrias\AddOrSelectBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class EntityDataTransformer implements DataTransformerInterface
{
	private $em;

	private $class;

	private $base_property;

	public function __construct(RegistryInterface $registry, $entity_class, $property)
	{
		$this->em = $registry->getManager();
		$this->class = $entity_class;
		$this->base_property = $property;
	}

	/**
	 * Transforms entities into string 
	 *
	 * @param Collection | null $tagCollection A collection of entities or NULL
	 *
	 * @return string | null An string representing the entity
	 * @throws UnexpectedTypeException
	 */
	public function transform($data)
	{
		if($data === null || $data == '')
			return "";

		return $data->getId();

	}

	/**
	 * Transforms string into entities.
	 *
	 * @param string | null $data Input string data
	 *
	 * @return Collection | null
	 * @throws UnexpectedTypeException
	 * @throws AccessDeniedException
	 */
	public function reverseTransform($data)
	{
		if(!$data)
			return null;

		if($data instanceof $this->class)
			return $data;

		$em = $this->em;
		$entity = $em->getRepository($this->class)->findOneBy(array('id' => $data));

		if(!$entity)
			$entity = $em->getRepository($this->class)->findOneBy(array('name' => $data));

		if(null === $entity){
			throw new TransformationFailedException(sprintf(
				'An entity with id number "%s" does not exist!',
				$data
			));
		}

		return $entity;
	}
}
