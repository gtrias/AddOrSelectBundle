<?php

namespace gtrias\AddOrSelectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('gtriasAddOrSelectBundle:Default:index.html.twig', array('name' => $name));
    }
}
