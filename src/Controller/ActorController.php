<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Program;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

///**
// * @Route("program/{slug}/actor")
// */
class ActorController extends AbstractController
{
    /**
     * @Route("/actor", name="actor_index", methods={"GET"})
     */
    public function index(ActorRepository $actorRepository): Response
    {

        return $this->render('actor/index.html.twig', [
        'actors' => $actorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/actor/new", name="actor_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $actor->setSluga($slugify->generate($actor->getName()));
            $entityManager->persist($actor);
            $entityManager->flush();

            return $this->redirectToRoute('actor_index');
        }

        return $this->render('actor/new.html.twig', [
            'actor' => $actor,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/actor/{sluga}", name="actor_show", methods={"GET"})
     */
    public function show(Actor $actor):Response
    {
        $programs = $this->getDoctrine()
        ->getRepository(Actor::class)
        ->find($actor)
        ->getPrograms();

        return $this->render('actor/show.html.twig',[
        'actor' => $actor, 'programs' => $programs
        ]);
    }

    /**
     * @Route("/actor/{sluga}/edit", name="actor_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Actor $actor): Response
    {
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        $sluga = $actor->getSluga();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('actor_index');
        }

        return $this->render('actor/edit.html.twig', [
            'actor' => $actor,
            'form' => $form->createView(),
            'sluga' => $sluga,
        ]);
    }

    /**
     * @Route("/actor/{sluga}", name="actor_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Actor $actor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('actor_index');
    }
}
