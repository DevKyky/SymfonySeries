<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// préfixe des routes du controller
#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        // Récupère toutes les séries en BDD
        // $series = $serieRepository->findAll();
        //$series = $serieRepository->findBy([], ['popularity' => 'DESC'], 50);
        $series = $serieRepository->findBestSeries(1);

        return $this->render('serie/list.html.twig', [
            "series" => $series
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    #[ParamConverter('serie', class: 'App\Entity\Serie')]
    public function show(SerieRepository $serieRepository, Serie $serie): Response
    {
        // Sans ParamConverter
        /*        $serie = $serieRepository->find($id);

                if(!$serie) {
                    throw $this->createNotFoundException("Ooops! Serie not found !");
                }*/

        return $this->render('serie/show.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, SerieRepository $serieRepository): Response
    {
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            dump($serie);

            // Mise à jour de l'entité
            $serie->setDateCreated(new \DateTime());
            $genres = $serieForm->get('genres')->getData();
            $serie->setGenres(implode(" / ", $genres));

            // Enregistrement des données
            $serieRepository->add($serie, true);

            // Feedback user
            $this->addFlash('success', 'Serie added !');

            // Redirection vers la page de détail
            return $this->redirectToRoute('serie_show', [
                'id' => $serie->getId()
            ]);
        }

        return $this->render('serie/add.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, SerieRepository $serieRepository, int $id): Response
    {
        $serie = $serieRepository->find($id);
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            // Mise à jour de l'entité
            $serie->setDateModified(new \DateTime());
            $genres = $serieForm->get('genres')->getData();
            $serie->setGenres(implode(" / ", $genres));

            // Enregistrement des données
            $serieRepository->add($serie, true);

            // Feedback user
            $this->addFlash('success', 'Serie edited !');

            // Redirection vers la page de détail
            return $this->redirectToRoute('serie_show', [
                'id' => $serie->getId()
            ]);
        }

        return $this->render('serie/edit.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }
}
