<?php

namespace Onadrog\ImageConverterBundle\Mock\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        return $this->render('index.html.twig');
    }
}
