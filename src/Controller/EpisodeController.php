<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\User;
use App\Entity\Season;
use App\Form\CommentType;
use App\Form\EpisodeType;

use App\Repository\CommentRepository;
use App\Repository\EpisodeRepository;
use App\Repository\UserRepository;
use App\Service\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{slug}/season/{number}/episode")
 */
class EpisodeController extends AbstractController
{
    /**
     * @Route("/", name="episode_index", methods={"GET"})
     */
    public function index(Program $program, Season $season): Response
    {
        $episodes = $season->getEpisodes();
        $seasonNb = $season->getNumber();
        $slug = $program->getSlug();
        $programTitle = $program->getTitle();


        return $this->render('episode/index.html.twig', [
            'episodes' => $episodes,
            'slug' => $slug,
            'number' => $seasonNb,
            'program' => $programTitle,
        ]);
    }

    /**
     * @Route("/new", name="episode_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $episode->setSluge($slugify->generate($episode->getTitle()));
            $entityManager->persist($episode);
            $entityManager->flush();

            return $this->redirectToRoute('episode_index');
        }

        return $this->render('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{sluge}", name="episode_show")
     */
    public function show($sluge, Season $season, Program $program, EpisodeRepository $episodeRepository,
                         CommentRepository $commentRepository, Request $request, EntityManagerInterface $em, Slugify $slugify): Response
    {

        $episode = $episodeRepository->findOneBySluge($sluge);
        $episodeId = $episode->getId();
        $comments = $commentRepository->findByEpisode($episodeId);

      /*  dump($comments);
        die();*/


        $seasonNb = $season->getNumber();
        $slug = $program->getSlug();
        $programTitle = $program->getTitle();

        /*dump($user);
        die();*/
        $user = $this->getUser();
        if (!$user) {
            return $this->render('episode/show.html.twig', [
                'episode' => $episode,
                'slug' => $slug,
                'number' => $seasonNb,
                'program' => $programTitle,
                'comments' => $comments,
            ]);
        } else {
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /*dump($comment);
                die();*/
                $comment->setAuthor($user);
                $comment->setEpisode($episode);
                $em->persist($comment);
                $em->flush();
                $episodes = $season->getEpisodes();
                return $this->redirectToRoute('episode_index', [
                    'episodes' => $episodes,
                    'slug' => $slug,
                    'number' => $seasonNb,
                    'program' => $programTitle,
                ]);
            }
            /*
            dump($comment);
            die();*/

            return $this->render('episode/show.html.twig', [
                'episode' => $episode,
                'slug' => $slug,
                'number' => $seasonNb,
                'program' => $programTitle,
                'comments' => $comments,
                'form' => $form->createView(),
            ]);
        }


    }

    /**
     * @Route("/{sluge}/edit", name="episode_edit", methods={"GET","POST"})
     */
    public function edit($sluge, Season $season, Program $program, Request $request, EpisodeRepository $episodeRepository): Response
    {
        $episode = $episodeRepository->findOneBySluge($sluge);

        $seasonNb = $season->getNumber();
        $slug = $program->getSlug();

        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('episode_index');
        }

        return $this->render('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
            'slug' => $slug,
            'number' => $seasonNb,

        ]);
    }

    /**
     * @Route("/{sluge}", name="episode_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Episode $episode): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('episode_index');
    }
}
