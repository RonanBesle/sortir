<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\FiltreSortieType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     * @Route("/", name="main_home")
     */
    public function list(Request $request, SortieRepository $sortieRepository): Response
    {
        $form = $this->createForm(FiltreSortieType::class, null, [
            'campus_choices' => $this->getCampusChoices(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $campus = $data['campus'];

            // Utilisez $campus pour filtrer les sorties
            $sorties = $sortieRepository->findByCampus($campus);
        } else {
            // Si le formulaire n'est pas soumis, affichez toutes les sorties
            $sorties = $sortieRepository->findAll();
        }

        return $this->render('sortie/listSortie.html.twig', [
            'sorties' => $sorties,
            'form' => $form->createView(),
        ]);
    }

    private function getCampusChoices()
    {
        return $this->getDoctrine()
            ->getRepository(Campus::class)
            ->createQueryBuilder('c')
            ->select('c.id', 'c.nom')
            ->getQuery()
            ->getResult();
    }
}
