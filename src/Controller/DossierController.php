<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\Fichier;
use App\Form\DossierType;
use App\Repository\DossierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/application/dossier')]
final class DossierController extends AbstractController
{
    #[Route(name: 'app_dossier_index', methods: ['GET'])]
    public function index(DossierRepository $dossierRepository): Response
    {
        return $this->render('dossier/index.html.twig', [
            'dossiers' => $dossierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dossier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dossier = new Dossier();
        $form = $this->createForm(DossierType::class, $dossier);
        $form->handleRequest($request);
        $file = $form->get('ext')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($file) {
                $ext = $file->guessExtension();
                $newFilename = $dossier->getToken() . '.' . $ext;
                $pathUpload = "../public/img/imgDossier";
                try {
                    $file->move(
                        $pathUpload,
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $dossier->setExt($ext);
            }
            $entityManager->persist($dossier);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier/new.html.twig', [
            'dossier' => $dossier,
            'form' => $form,
        ]);
    }

    #[Route('/{token}', name: 'app_dossier_show', methods: ['GET'])]
    public function show($token, EntityManagerInterface $entityManager): Response
    {
        $dossier = $entityManager->getRepository(Dossier::class)->findOneByToken($token);
        $fichiers = $entityManager->getRepository(Fichier::class)->findByFolder($dossier);
        return $this->render('dossier/show.html.twig', [
            'dossier' => $dossier,
            'fichiers' => $fichiers,
        ]);
    }

    #[Route('/{token}/edit', name: 'app_dossier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $token, EntityManagerInterface $entityManager): Response
    {
        $dossier = $entityManager->getRepository(Dossier::class)->findOneByToken($token);
        $form = $this->createForm(DossierType::class, $dossier);
        $form->handleRequest($request);
        $file = $form->get('ext')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($file) {
                $ext = $file->guessExtension();
                $newFilename = $dossier->getToken() . '.' . $ext;
                $pathUpload = "../public/img/imgDossier";
                try {
                    $file->move(
                        $pathUpload,
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $dossier->setExt($ext);
            }
            $entityManager->persist($dossier);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier/edit.html.twig', [
            'dossier' => $dossier,
            'form' => $form,
        ]);
    }

    #[Route('/{token}', name: 'app_dossier_delete', methods: ['POST'])]
    public function delete(Request $request, $token, EntityManagerInterface $entityManager): Response
    {
        $dossier = $entityManager->getRepository(Dossier::class)->findOneByToken($token);
        if ($this->isCsrfTokenValid('delete'.$dossier->getToken(), $request->getPayload()->getString('_token'))) {
            $fichiers = $entityManager->getRepository(Fichier::class)->findBy(['folder' => $dossier]);

            $fichierDirectory = $this->getParameter('kernel.project_dir') . '/public/img/imgFichier/';

            foreach ($fichiers as $fichier) {
                $filePath = $fichierDirectory . $fichier->getToken() . '.' . $fichier->getExt();
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $entityManager->remove($fichier);
            }

            $dossierDirectory = $this->getParameter('kernel.project_dir') . '/public/img/imgDossier/';
            $dossierFile = glob($dossierDirectory . $dossier->getToken() . '.*');
            foreach ($dossierFile as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            $entityManager->remove($dossier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dossier_index', [], Response::HTTP_SEE_OTHER);
    }
}
