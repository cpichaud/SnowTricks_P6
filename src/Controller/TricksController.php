<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TricksController extends AbstractController
{
    #[Route('/{tricks}', name: '_profiler_detail_trick', methods: ['GET'])]
    public function showTrick(TrickRepository $trick, Request $request, $tricks): Response
    {
        $detailTrick = $trick->findOneBy(array('title' => $tricks));
       // $pictures = $trick->getPictures();

        return $this->render('tricks\detail_trick.html.twig', [
            'detailTrick' => $detailTrick,
        ]);
    }

    #[Route('/edit/{id}', name: '_profiler_edit_trick')]
    public function editTrick(TrickRepository $trickRepo, Request $request, $id): Response
    {

        $trick = $trickRepo->findOneById($id);
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
   
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications dans la base de données
            $trick->setDateUpdate(new \DateTime());

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre trick a été modifié avec succès !');
            return $this->redirectToRoute('_profiler_detail_trick');
        }
    
        return $this->render('tricks\edit_trick.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}
