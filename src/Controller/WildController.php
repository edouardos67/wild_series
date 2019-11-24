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
        return $this->render('wild/index.html.twig',[
        'website' => 'Wild Séries'
        ]);
    }

    /**
     * @Route("/wild/show/{slug}", name="wild_show")
     */
    public function show(string $slug): Response
    {
        $pattern = "/^[a-z0-9-]+$/";
        if (preg_match($pattern,$slug)) {
            $nomSerie = str_replace("-"," ",$slug);
            $nomSerie = ucwords($nomSerie);
            return $this->redirectToRoute('wild_show',['nomSerie' => $nomSerie]);
        }
        if ($slug === '') {
            $nomSerie = "Aucune série sélectionnée, veuillez choisir une série";
            return $this->render('wild/show.html.twig',[
            'nomSerie' => $nomSerie
            ]);
        }
    }

    /**
     * @Route("/wild/new", name="wild_new")
     */

    public function new(): Response
    {
        // traitement d'un formulaire par exemple

        // redirection vers la page 'wild_show', correspondant à l'url wild/show/5
        return $this->redirectToRoute('wild_show', ['page' => 5]);
    }

}

