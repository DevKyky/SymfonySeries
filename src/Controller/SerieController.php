<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SeasonRepository;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// préfixe des routes du controller
#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ['page' => '\d+'])]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {
        $maxSeries = $serieRepository->count([]);
        $maxPage = ceil($maxSeries / 50);

        if ($page < 1 || $page > $maxPage) {
            throw $this->createNotFoundException('Oops! Page not found');
        }

        // Récupère toutes les séries en BDD
        // $series = $serieRepository->findAll();
        //$series = $serieRepository->findBy([], ['popularity' => 'DESC'], 50);
        $series = $serieRepository->findBestSeries($page);

        return $this->render('serie/list.html.twig', [
            "series" => $series,
            "currentPage" => $page,
            "maxPage" => $maxPage
        ]);
    }

    #[Route('/detail/{id}', name: 'show', requirements: ['id' => '\d+'])]
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
        // throw $this->createAccessDeniedException('Access Denied.');

        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            dump($serie);

            // Mise à jour de l'entité
            $serie->setDateCreated(new \DateTime());
            $genres = $serieForm->get('genres')->getData();
            $serie->setGenres(implode(" / ", $genres));

            // Gestion de l'upload
            $image = $serieForm->get('backdrop')->getData();
            /**
             * @var UploadedFile $image
             * permet d'accéder à l'autocomplétion quand le type d'une variable n'est pas connu
             */
            $newFilename = $serie->getName() . '-' . uniqid() . '.' . $image->guessExtension();

            try {
                $image->move($this->getParameter('serie_backdrop_directory'), $newFilename);
                $serie->setBackdrop($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'An issue happened.');
                dump($e->getMessage());
            }

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
        // throw $this->createAccessDeniedException('Access Denied.');

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

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(SerieRepository $serieRepository, SeasonRepository $seasonRepository, int $id): Response
    {
        $serie = $serieRepository->find($id);
        $serieRepository->remove($serie, true);

//        dd($serie);

        $this->addFlash('success', 'Season deleted');

        return $this->redirectToRoute('serie_list');
    }
}
