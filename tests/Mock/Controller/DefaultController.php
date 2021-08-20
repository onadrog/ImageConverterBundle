<?php

namespace Onadrog\ImageConverterBundle\Mock\Controller;

use Onadrog\ImageConverterBundle\Mock\Entity\Entity\Media;
use Onadrog\ImageConverterBundle\Mock\Type\MockType;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    public function form(Request $request): Response
    {
        //$medium = new Media();
        $refClass = new ReflectionClass("Onadrog\ImageConverterBundle\Mock\Entity\Entity\\" . $request->get('entity'));
        $medium = $refClass->newInstance();
        $form = $this->createForm(MockType::class, $medium);
       $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($medium);
            $em->flush();

            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('form.html.twig', [
            'medium' => $medium,
            'form' => $form,
        ]);
    }
}
