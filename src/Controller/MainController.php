<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/test', name: 'main_test')]
    public function test(): Response
    {
        $username = "Kyky #666";
        $serie = [
            'title' => 'Game of Thrones',
            'year' => 2011
        ];

        return $this->render( 'main/test.html.twig', [
            'username' => $username,
            'serie' => $serie
        ]);

    }
}
