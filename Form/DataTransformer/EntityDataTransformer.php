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

	public function __construct(RegistryInterface $registry, $entity_class)
	{
		$this->em = $registry->getManager();
		$this->class = $entity_class;
	}

	/**
	 * Transforms entities into string 
	 *
	 * @param Collection | null $tagCollection A collection of entities or NULL
	 *
	 * @return string | null An string representing the entity
	 * @throws UnexpectedTypeException
	 */
	public function transform($entity)
	{
		//ldd($entity);
		//
		//transform($entityCollection);
		return $entity;
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
		ldd($data);
		$entityCollection = new ArrayCollection();

		if ('' === $data || null === $data) {
			return $tagCollection;
		}

		if (!is_string($data)) {
			throw new UnexpectedTypeException($data, 'string');
		}

		$tag = $this->em->getRepository($this->class)
			->findOneBy(array('name' => $name));

		if (null === $tag) {
			$entity = new $class();
			$entity->setName($name);

			$this->em->persist($entity);
		}

		$entityCollection->add($entity);

		return $entityCollection;
	}
}
