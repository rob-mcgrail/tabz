<?php

namespace TabApp\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function homepageAction()
    {
        return $this->render('TabAppHomeBundle:Home:default.html.twig');
    }
}
