<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieIndexType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="app_sortie_index", methods={"GET", "POST"} )
     */
    public function index(Request $request, SortieRepository $sortieRepository, Security $security): Response
    {


        // Création d'un formulaire pour filtrer les sorties
        $form = $this->createForm(SortieIndexType::class);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $campus = $data->getcampus();
            $nomSortie = $data->getNom();
            $dateDebut = $data->getDateHeureDebut();
            $organisateur = $data->getOrganisateur();
            $currentUser = $this->getUser();


            $sorties = $sortieRepository->findByFilters($campus, $nomSortie, $dateDebut, $organisateur, $currentUser);

        } else {
            // Si le formulaire n'est pas soumis, affichez toutes les sorties
            $sorties = $sortieRepository->findAll();
        }


        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="app_sortie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SortieRepository $sortieRepository): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->add($sortie, true);


            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_sortie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        $form = $this->createForm(SortieIndexType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->add($sortie, true);

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/inscription", name="app_sortie_delete", methods={"POST"})
     */
    public function delete(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $sortieRepository->remove($sortie, true);
        }

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/desister", name="app_sortie_inscription", methods={"POST"})
     */

    public function inscription(int $id): Response
    {
        // Récupérer l'utilisateur connecté (si nécessaire)
        $user = $this->getUser();

        // Récupérer la sortie par son ID
        $sortie = $this->getDoctrine()->getRepository(Sortie::class)->find($id);

        // Ajouter l'utilisateur à la liste des inscrits pour cette sortie
        $sortie->addUser($user);

        // Enregistrer les modifications dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($sortie);
        $entityManager->flush();

        // Rediriger vers la page de la sortie (ou une autre page appropriée)
        return $this->redirectToRoute('app_sortie_index', ['id' => $id]);


    }


    /**
     * @Route("/{id}", name="app_sortie_desister", methods={"POST"})
     */
    public function desister(int $id): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer la sortie par son ID
        $sortie = $this->getDoctrine()->getRepository(Sortie::class)->find($id);

        // Ajouter l'utilisateur à la liste des inscrits pour cette sortie
        $sortie->removeUser($user);

        // Enregistrer les modifications dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($sortie);
        $entityManager->flush();

        // Rediriger vers la page de la sortie (ou une autre page appropriée)
        return $this->redirectToRoute('app_sortie_index', ['id' => $id]);
        }

}