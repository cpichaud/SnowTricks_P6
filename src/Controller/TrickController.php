<?php

namespace App\Controller;

use DateTime;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    #[Route('/trick', name: 'app_trick')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TrickController.php',
        ]);
    }

    #[Route('/{tricks}', name: 'trick_show')]
    public function show(
        TrickRepository $trick, 
        CommentRepository $commentRepo, 
        $tricks, Request $request, 
        EntityManagerInterface $entityManager
    ): Response
    {
        $detailTrick = $trick->findOneBy(['name' => $tricks]);
        $showComments = $commentRepo->findBy(['trick' => $detailTrick], ['createdAt' => 'DESC']);

        // Ajouter un commentaire
        $addComment = new Comment();
        $form = $this->createForm(CommentType::class, $addComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications dans la base de données
            //dd($this->getUser());
            $createdAt = new \DateTimeImmutable();
            $addComment->setCreatedAt($createdAt);
            $addComment->setTrick($detailTrick);
            $addComment->setAuthor($this->getUser());

            $entityManager->persist($addComment);
            $entityManager->flush();

            //$this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre Commentaire à été ajouté !');
            //return $this->redirectToRoute('trick_show');
        }
    
        return $this->render('tricks\show.html.twig', [
            'detailTrick' => $detailTrick,
            'showComments' => $showComments,
            'form' => $form->createView(),
        ]);
    }
}
