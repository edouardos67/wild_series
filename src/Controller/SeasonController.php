<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Program;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("program/{slug}/season")
 */
class SeasonController extends AbstractController
{
//    /**
//     * @Route("/season/", name="season_index", methods={"GET"})
//     */
    /*public function index(SeasonRepository $seasonRepository): Response
    {

        return $this->render('season/index.html.twig', [
            'seasons' => $seasonRepository->findAll(),
        ]);
    }*/

    /**
     * @Route("/new", name="season_new", methods={"GET","POST"})
     */
    public function new($slug, Request $request): Response
    {
        $season = new Season();
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($season);
            $entityManager->flush();

            return $this->redirectToRoute('program_show',['slug' => $slug]);
        }

        return $this->render('season/new.html.twig', [
            'season' => $season,
            'form' => $form->createView(),
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/{number}", name="season_show", methods={"GET"})
     */
    public function show(Season $season, Program $program): Response
    {
        /*dump($program);
        dump($season);
        die();*/
        $programTitle = $program->getTitle();
        $slug = $program->getSlug();

        return $this->render('season/show.html.twig', [
            'season' => $season,
            'programTitle' => $programTitle,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/{number}/edit", name="season_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Season $season, Program $program): Response
    {
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        $slug = $program->getSlug();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('season_index');
        }

        return $this->render('season/edit.html.twig', [
            'season' => $season,
            'slug' => $slug,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{number}", name="season_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Season $season): Response
    {
        if ($this->isCsrfTokenValid('delete'.$season->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($season);
            $entityManager->flush();
        }

        return $this->redirectToRoute('season_index');
    }
}
