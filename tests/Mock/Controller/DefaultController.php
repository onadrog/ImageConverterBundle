<?php

namespace Onadrog\ImageConverterBundle\Mock\Controller;

use Onadrog\ImageConverterBundle\Mock\Type\MockType;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    public function index()
    {
        return $this->render('index.html.twig');
    }

    public function form(Request $request)
    {
        $refClass = new ReflectionClass("Onadrog\ImageConverterBundle\Mock\Entity\Entity\\".$request->get('entity'));
        $form = $this->createForm(MockType::class, $refClass->newInstance());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect('/');
        }

        return $this->renderForm('form.html.twig', ['form' => $form]);
    }
}
