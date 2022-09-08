<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/series', name: 'api_series_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'getAll', methods: ['GET'])]
    public function getAll(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();
        return $this->json($series, 200, [], ['groups' => 'serie_group']);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function getOne(int $id, SerieRepository $serieRepository, SerializerInterface $serializer): Response
    {
        $serie = $serieRepository->find($id);
        return $this->json($serie, 200, [], ['groups' => 'serie_group']);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function add(Request $request, SerializerInterface $serializer, SerieRepository $serieRepository): Response
    {
        $json = $request->getContent();
        $serie = $serializer->deserialize($json, Serie::class, 'json');
        $serie->setDateCreated(new \DateTime());
        $serieRepository->add($serie, true);

        return $this->json("OK");
    }
}
