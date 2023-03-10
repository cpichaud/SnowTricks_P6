<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TricksController extends AbstractController
{
    #[Route('/{tricks}', name: '_profiler_detail_trick')]
    public function showTrick(
        TrickRepository $trick, 
        CommentRepository $commentRepo, 
        $tricks, Request $request, 
        EntityManagerInterface $entityManager
    ): Response
    {
        $detailTrick = $trick->findOneBy(['title' => $tricks]);
        $showComments = $commentRepo->findBy(['tricks' => $detailTrick], ['date_created' => 'DESC']);
        //$user = $commentRepo->findoneBy(['tricks' => $detailTrick], ['date_created' => 'DESC']);

        // Ajouter un commentaire
        $addComment = new Comment();
        $form = $this->createForm(CommentType::class, $addComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications dans la base de données
            
            $addComment->setTricks($detailTrick->getId());
            $addComment->setDateCreated(new \DateTime());
            $addComment->setUsers($this->getUser());

            $entityManager->persist($addComment);
            $entityManager->flush();

            //$this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre Commentaire à été ajouté !');
            return $this->redirectToRoute('_profiler_detail_trick');
        }
    

    
        return $this->render('tricks\detail_trick.html.twig', [
            'detailTrick' => $detailTrick,
            'showComments' => $showComments,
            'form' => $form->createView(),
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

            //$this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre trick a été modifié avec succès !');
            return $this->redirectToRoute('_profiler_detail_trick');
        }
    
        return $this->render('tricks\edit_trick.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}
