<?php

namespace gtrias\AddOrSelectBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use gtrias\AddOrSelectBundle\DependencyInjection\Compiler\FormPass;

class gtriasAddOrSelectBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FormPass());
    }
}
