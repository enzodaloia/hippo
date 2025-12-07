<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\Portfolio;
use App\Entity\Fichier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\DossierRepository;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $portfolio = $entityManager->getRepository(Portfolio::class)->findAll();
        return $this->render('index/index.html.twig', [
            'portfolio'=>$portfolio
        ]);
    }

    #[Route('/dossiers', name: 'app_dossiers')]
    public function dossiers(EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response {
        $dossierRepository = $entityManager->getRepository(Dossier::class);
        $query = $dossierRepository->createQueryBuilder('d')
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('index/dossiers.html.twig', [
            'dossiers' => $pagination,
        ]);
    }

    #[Route('/fichiers/{token}', name: 'app_fichiers')]
    public function fichiers($token, EntityManagerInterface $entityManager, \Knp\Component\Pager\PaginatorInterface $paginator): Response
    {
        $dossier = $entityManager->getRepository(Dossier::class)->findByToken($token);
        $libelle = $dossier[0]->getLibelle();
        $query = $entityManager->getRepository(Fichier::class)
            ->createQueryBuilder('f')
            ->where('f.folder = :dossier')
            ->setParameter('dossier', $dossier)
            ->orderBy('f.createdAt', 'DESC')
            ->getQuery();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('index/fichiers.html.twig', [
            'fichiers' => $pagination,
            'libelle' => $libelle
        ]);
    }
}
