<?php

namespace Onadrog\ImageConverterBundle\Mock\Controller;

use Onadrog\ImageConverterBundle\Mock\Entity\Entity\SoloFile;
use Onadrog\ImageConverterBundle\Mock\Entity\Repository\SoloFileRepository;
use Onadrog\ImageConverterBundle\Mock\Type\MockType;
use Onadrog\ImageConverterBundle\Mock\Type\SoloType;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class DefaultController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index(SoloFileRepository $repo): Response
    {
        $soloFile = $repo->find(1);

        return $this->render('index.html.twig', [
            'val' => $soloFile,
        ]);
    }

    #[Route(path: '/form/{entity}/{property}', name: 'form')]
    public function form(Request $request): Response
    {
        //$medium = new SoloFile();
        $refClass = new ReflectionClass("Onadrog\ImageConverterBundle\Mock\Entity\Entity\\".$request->get('entity'));
        $medium = $refClass->newInstance();
        $form = $this->createForm(MockType::class, $medium, ['label' => $request->get('property')]);
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

    #[Route(path: '/form/solofile/{id}/edit', name: 'solofile')]
    public function formsoloedit(Request $request, $id): Response
    {
        $soloFile = $this->getDoctrine()->getRepository(SoloFile::class)->find($id);
        $form = $this->createForm(SoloType::class, $soloFile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('editsolofile.html.twig', [
            'soloFile' => $soloFile,
            'form' => $form,
        ]);
    }

    #[Route(path: '/form/solofile/{id}/delete', name: 'solofile_detete')]
    public function formSoloDelete($id): Response
    {
        $soloFile = $this->getDoctrine()->getRepository(SoloFile::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($soloFile);
        $entityManager->flush();

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }
}
