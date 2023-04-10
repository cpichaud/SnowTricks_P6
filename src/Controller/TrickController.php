<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

        // Récupérez l'utilisateur associé à la trick
        $user = $detailTrick->getUser();
        
        // Récupérez l'email de l'utilisateur
        $email = $user->getEmail();
        // Ajouter un commentaire
        $addComment = new Comment();
        $form = $this->createForm(CommentType::class, $addComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new \DateTimeImmutable();
            $addComment->setCreatedAt($createdAt);
            $addComment->setTrick($detailTrick);
            $addComment->setAuthor($this->getUser());

            $entityManager->persist($addComment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre Commentaire à été ajouté !');

            return $this->render('tricks\show.html.twig', [
                'detailTrick' => $detailTrick,
                'showComments' => $showComments,
                'form' => $form->createView(),
                'email' => $email
            ]);
        }
    
        return $this->render('tricks\show.html.twig', [
            'detailTrick' => $detailTrick,
            'showComments' => $showComments,
            'form' => $form->createView(),
            'email' => $email
        ]);
    }


    /**
    * @Route("/trick/new", name="trick_new", methods={"GET","POST"})
    */
    public function new(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $trick->setCreatedAt(new \DateTimeImmutable());
            $trick->setUpdatedAt(new \DateTimeImmutable());
            $trick->setUser($this->getUser()); // Set the current user as the author

            // Handle main image upload
            $mainImage = $form->get('mainImage')->getData();
            if ($mainImage) {
                $mainImageFilename = $this->uploadImage($mainImage);
                if ($mainImageFilename) {
                    $trick->setMainImage($mainImageFilename);
                }
            }

            // Handle additional images upload
            foreach ($trick->getImages() as $image) {
                $uploadedFile = $image->getImageFile();
                if ($uploadedFile) {
                    $filename = $this->uploadImage($uploadedFile);
                    if ($filename) {
                        $image->setPath($filename);
                        $image->setCreatedAt(new \DateTimeImmutable());
                    }
                }
            }

            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('tricks/create.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    // ...

    private function uploadImage($file): ?string
    {
        $uploadDirectory = $this->getParameter('upload_directory');
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $uploadDirectory,
                $newFilename
            );
        } catch (FileException $e) {
            // Unable to upload the image, return null
            return null;
        }

        return $newFilename;
    }
    
    /**
     * @Route("/trick/{id}/delete", name="trick_delete", methods={"GET"})
     */
    public function deleteTrick(int $id, TrickRepository $trickRepository, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {
        $trick = $trickRepository->find($id);
        $comments = $commentRepository->findBy(['trick' => $trick]);

        if (!$trick) {
            throw $this->createNotFoundException('Trick not found');
        }

        if ($this->getUser() === $trick->getUser()) {
            foreach ($trick->getVideos() as $video) {
                $trick->removeVideo($video);
                $entityManager->remove($video);
            }
            foreach ($trick->getImages() as $video) {
                $trick->removeImage($video);
                $entityManager->remove($video);
            }
            foreach ($comments as $comment) {
                $entityManager->remove($comment);
            }
            $entityManager->remove($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Trick deleted successfully.');

            return $this->redirectToRoute('app_home');
        } else {
            $this->addFlash('error', 'You can only delete tricks that you created.');

            return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
        }
    }

    /**
     * @Route("/comment/{id}/delete", name="comment_delete", methods={"GET"})
     */
    public function deleteComment(
        int $id,
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Récupérer le commentaire par son ID
        $comment = $commentRepository->find($id);

        // Vérifier si le commentaire existe et si l'utilisateur connecté est l'auteur du commentaire
        if ($comment && $this->getUser() === $comment->getAuthor()) {
            // Supprimer le commentaire
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        if ($comment) {
            return $this->redirectToRoute('app_home');
        } else {
            // Rediriger vers une page d'erreur ou une autre page si le commentaire n'existe pas
            return $this->redirectToRoute('some_error_page');
        }
    }


}
