<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
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
    public function index(): Response
    {
        $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
            'No program found in program\'s table.'
            );
        }


        return $this->render('wild/index.html.twig', [
        'programs' => $programs]
        );
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

    /**
     * @param string $$categoryName
     * @Route("/category/{categoryName}", name="show_category")
     * @return Response
     */
    public function showByCategory(string $categoryName):Response
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
            'No program with '.$categoryName.' category, found in program\'s table.'
            );
        }

        /*var_dump($Category);
        die();*/

//        $idCat = $Category['id'];

        $programs = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findBy(['category' => $Category->getId()],['id' => 'desc'],3,0);

        return $this->render('/wild/category.html.twig',[
        'programs' => $programs,
        'categoryName'  => $categoryName
        ]);

    }
//    les commentaires dessous correspondent à l'état au moment de la quete_symfony_5'
//   ---------------------------------------------------------------------------------
//    /**
//     * @Route("/wild/show/{slug}",  name="wild_show")
//     */
    /*public function show(string $slug): Response
    {

        $pattern = "/^[a-z0-9-éèàùçâîê]+$/";
        if (preg_match($pattern, $slug) || $slug === '') {
            if ($slug !== "") {
                $nomSerie = str_replace("-", " ", $slug);
                $nomSerie = ucwords($nomSerie);
                return $this->render('wild/show.html.twig', ['nomSerie' => $nomSerie, 'slug' => $slug]);
            } else {
                return $this->showDefaut();
            }
        } else {
            return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries'
            ]);
        }

    }*/

//    /**
//     * @Route("/wild/show",  name="wild_show_defaut")
//     */
    /*public function showDefaut(): Response
    {
            return $this->render('wild/show.html.twig', [
            'nomSerie' => 'Aucune série sélectionnée, veuillez choisir une série'
            ]);
     }*/

}