<?php
namespace gtrias\AddOrSelectBundle\Form\DataMapper;

use Symfony\Component\Form\DataMapperInterface;

/**
 * @deprecated
 */
class AddOrSelectDataMapper implements DataMapperInterface
{
    private $_em;

    public function __construct($manager)
    {
        $this->_em = $manager;
    }

    public function mapDataToForms($data, $forms)
    {
        // ...
    }

    public function mapFormsToData($forms, &$data)
    {

        // ...
    }
}
