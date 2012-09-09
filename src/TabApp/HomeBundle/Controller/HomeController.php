<?php

namespace TabApp\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function anonymousAction()
    {
        return $this->render('TabAppHomeBundle:Home:anonymous.html.twig');
    }
}
