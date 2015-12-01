<?php

namespace Nucleus\DataBaseSwitchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DataBaseSwitchBundle:Default:index.html.twig', array('name' => $name));
    }
}
