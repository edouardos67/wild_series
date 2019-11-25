<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index(): Response
    {
        return $this->render('wild/index.html.twig', [
        'website' => 'Wild Séries'
        ]);
    }

    /**
     * @Route("/wild/show/{slug}",  name="wild_show")
     */
    public function show($slug): Response
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

    }

    /**
     * @Route("/wild/show",  name="wild_show_defaut")
     */
    public function showDefaut(): Response
    {
            return $this->render('wild/show.html.twig', [
            'nomSerie' => 'Aucune série sélectionnée, veuillez choisir une série'
            ]);
     }

}