<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// préfixe des routes du controller
#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(): Response
    {
        // TODO renvoyer la liste des séries
        return $this->render('serie/list.html.twig', [

        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show($id): Response
    {
        dump($id);

        // TODO renvoyer détail d'une série
        return $this->render('serie/show.html.twig', [
            'noSerie' => $id
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(): Response
    {
        // TODO création d'une série
        return $this->render('serie/add.html.twig');
    }
}
