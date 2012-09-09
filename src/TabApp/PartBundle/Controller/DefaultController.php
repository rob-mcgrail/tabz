<?php

namespace TabApp\PartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $vars = array('name' => 'somevar');
        return $this->render('TabAppPartBundle:Default:index.html.twig', $vars);
    }
}
