<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\Fichier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
        ]);
    }

    #[Route('/dossiers', name: 'app_dossiers')]
    public function dossiers(EntityManagerInterface $entityManager): Response
    {
        $dossiers = $entityManager->getRepository(Dossier::class)->findAll();
        return $this->render('index/dossiers.html.twig', [
            'dossiers'=>$dossiers
        ]);
    }

    #[Route('/fichiers/{token}', name: 'app_fichiers')]
    public function fichiers($token, EntityManagerInterface $entityManager): Response
    {
        $dossier = $entityManager->getRepository(Dossier::class)->findByToken($token);
        $libelle = $dossier[0]->getLibelle();
        $fichiers = $entityManager->getRepository(Fichier::class)->findByFolder($dossier);
        return $this->render('index/fichiers.html.twig', [
            'fichiers'=>$fichiers,
            'libelle'=>$libelle
        ]);
    }
}
