<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Actor;
use App\Form\ProgramSearchType;
use App\Service\Slugify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/wild", name="wild_index")
     * @return Response A response instance
     */
    public function index(Request $request): Response
    {


        $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
            'No program found in program\'s table.'
            );
        }



        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show")
     * @return Response
     */
    public function show(?string $slug):Response
    {
        if (!$slug) {
            throw $this
            ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
        '/-/',
        ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
            'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
        'program' => $program,
        'slug'  => $slug,
        ]);
    }

//    /**
//     * @param string $$categoryName
//     * @Route("/category/{categoryName}", name="show_category")
//     * @return Response
//     */
/*    public function showByCategory(string $categoryName):Response
    {
        if (!$categoryName) {
            throw $this
            ->createNotFoundException('No category has been sent to find a program in program\'s table.');
        }

        $Category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findOneBy(['name' => ucfirst(mb_strtolower($categoryName))]);
        if (!$Category) {
            throw $this->createNotFoundException(
            'No program with '.$categoryName.' category found in program\'s table.'
            );
        }

        $programs = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findBy(['category' => $Category->getId()],['id' => 'desc'],3,0);

        return $this->render('/wild/category.html.twig',[
        'programs' => $programs,
        'categoryName'  => $categoryName
        ]);

    }*/

    /**
     * @param string $programName
     * @Route("/program/{programName<^[a-z0-9-]+$>}", name="show_program")
     * @return Response
     */
    public function showByProgram(string $programName):Response
    {
        if (!$programName) {
            throw $this
            ->createNotFoundException('No program has been sent to find seasons in program\'s table.');
        }
        $program = preg_replace(
        '/-/',
        ' ', ucwords(trim(strip_tags($programName)), "-")
        );

        $seasons = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['title' => $program])
        ->getSeasons();
        if (!$seasons) {
            throw $this->createNotFoundException(
            'No seasons in '.$program.' program found in program\'s table.'
            );
        }

//        var_dump($seasons);
        /*var_dump($program);
        die();*/

        return $this->render('/wild/showSeasons.html.twig',[
        'seasons' => $seasons,
        'program'  => $program,
        'programName' => $programName
        ]);

    }

    /**
     * @param string $id
     * @Route("/program/{programName<^[a-z0-9-]+$>}/{saisonId}", name="show_program_season")
     * @return Response
     */
    public function showBySeason(string $programName, int $saisonId):Response
    {
        if (!$saisonId) {
            throw $this->createNotFoundException(
            'No season n° '.$saisonId.' for this program.'
            );
        }

        if (!$programName) {
            throw $this
            ->createNotFoundException('No program has been sent to find seasons in program\'s table.');
        }

        $seasonNb = $this->getDoctrine()
        ->getRepository(Season::class)
        ->findOneBy(['id'=>$saisonId])
        ->getNumber();

        $seasonDesc = $this->getDoctrine()
        ->getRepository(Season::class)
        ->findOneBy(['id'=>$saisonId])
        ->getDescription();

        $programId = $this->getDoctrine()
        ->getRepository(Season::class)
        ->findOneBy(['id'=>$saisonId])
        ->getProgram();

        $programTitle = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['id'=>$programId])
        ->getTitle();

        $episodes = $this->getDoctrine()
        ->getRepository(Season::class)
        ->findOneBy(['number' => $seasonNb])
        ->getEpisodes();


        if (!$episodes) {
            throw $this->createNotFoundException(
            'No episodes found in this season.'
            );
        }

        return $this->render('/wild/showEpisodes.html.twig',[
        'seasonNb' => $seasonNb,
        'seasonDesc' => $seasonDesc,
        'programTitle'  => $programTitle,
        'saisonId' => $saisonId,
        'episodes' => $episodes,
        ]);

    }

     /**
     * @Route("episode/{slug}", name="show_episode")
     */
    public function showEpisode(Episode $episode):Response
    {
        $season = $this->getDoctrine()
        ->getRepository(Episode::class)
        ->find($episode)
        ->getSeason();

        $program = $this->getDoctrine()
        ->getRepository(Season::class)
        ->find($season)
        ->getProgram();

        return $this->render('/wild/showOneEpisode.html.twig',[
            'episode' => $episode, 'season' => $season, 'program' => $program
        ]);
    }

}