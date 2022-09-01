<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function add(EntityManagerInterface $entityManager): Response
    {
        // TODO création d'une série

        $serie = new Serie();
        $serie->setBackdrop("azerty.png")
            ->setDateCreated(new \DateTime())
            ->setFirstAirDate(new \DateTime("-1 year"))
            ->setGenres("SF")
            ->setLastAirDate(new \DateTime())
            ->setName("Stargate SG1")
            ->setPopularity(9999)
            ->setPoster("poster.png")
            ->setStatus("Ended")
            ->setTmdbId(1234)
            ->setVote(10.0);

        dump($serie);

        $entityManager->persist($serie);
        $entityManager->flush();

        dump($serie);

        $serie->setName("Stargate Atlantis");
        $entityManager->persist($serie);
        $entityManager->flush();

        dump($serie);

        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->render('serie/add.html.twig');
    }
}
