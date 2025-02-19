<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\Fichier;
use App\Form\FichierType;
use App\Repository\DossierRepository;
use App\Repository\FichierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fichier')]
final class FichierController extends AbstractController
{
    #[Route(name: 'app_fichier_index', methods: ['GET'])]
    public function index(FichierRepository $fichierRepository): Response
    {
        return $this->render('fichier/index.html.twig', [
            'fichiers' => $fichierRepository->findAll(),
        ]);
    }

    #[Route('/new/{token}', name: 'app_fichier_new', methods: ['GET', 'POST'])]
    public function new($token,Request $request, EntityManagerInterface $entityManager, DossierRepository $dossierRepository): Response
    {
        $fichier = new Fichier();
        $dossier = $dossierRepository->findOneBy(['token' => $token]);
        $form = $this->createForm(FichierType::class, $fichier);
        $form->handleRequest($request);
        $file = $form->get('ext')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $fichier->setFolder($dossier);
            if ($file) {
                $ext = $file->guessExtension();
                $newFilename = $fichier->getToken() . '.' . $ext;
                $pathUpload = "../public/img/imgFichier";
                try {
                    $file->move(
                        $pathUpload,
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $fichier->setExt($ext);
            }
            $entityManager->persist($fichier);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_show', ['token'=>$fichier->getFolder()->getToken()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier/new.html.twig', [
            'fichier' => $fichier,
            'form' => $form,
        ]);
    }

    #[Route('/{token}', name: 'app_fichier_show', methods: ['GET'])]
    public function show($token, EntityManagerInterface $entityManager): Response
    {
        $fichier = $entityManager->getRepository(Fichier::class)->findOneByToken($token);
        return $this->render('fichier/show.html.twig', [
            'fichier' => $fichier,
        ]);
    }

    #[Route('/{token}/edit', name: 'app_fichier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $token, EntityManagerInterface $entityManager): Response
    {
        $fichier = $entityManager->getRepository(Fichier::class)->findOneByToken($token);
        $form = $this->createForm(FichierType::class, $fichier);
        $form->handleRequest($request);

        $file = $form->get('ext')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($file) {
                $ext = $file->guessExtension();
                $newFilename = $fichier->getToken() . '.' . $ext;
                $pathUpload = "../public/img/imgFichier";
                try {
                    $file->move(
                        $pathUpload,
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $fichier->setExt($ext);
            }
            $entityManager->persist($fichier);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_show', ['token'=>$fichier->getFolder()->getToken()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichier/edit.html.twig', [
            'fichier' => $fichier,
            'form' => $form,
        ]);
    }

    #[Route('/{token}', name: 'app_fichier_delete', methods: ['POST'])]
    public function delete(Request $request, $token, EntityManagerInterface $entityManager): Response
    {
        $fichier = $entityManager->getRepository(Fichier::class)->findOneByToken($token);
        if ($this->isCsrfTokenValid('delete'.$fichier->getToken(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($fichier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dossier_show', ['token'=> $fichier->getFolder()->getToken()], Response::HTTP_SEE_OTHER);
    }
}
