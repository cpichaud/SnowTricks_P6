<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
}

// /**
//  * @Route("/trick/new", name="trick_new")
//  */
// public function new(Request $request, EntityManagerInterface $entityManager): Response
// {
//     $trick = new Trick();

//     $image = new Image();
//     $trick->getImages()->add($image);

//     $form = $this->createForm(TrickType::class, $trick);

//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {

//         $trick->setUser($this->getUser());
//         $trick->setCreatedAt(new \DateTimeImmutable());
//         $trick->setUpdatedAt(new \DateTimeImmutable());

//         // foreach ($trick->getImages() as $image) {
//         //     $imageFile = $image->getImageFile();
//         //     if ($imageFile) {
//         //         $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
//         //         $imageFile->move($this->getParameter('images_directory'), $fileName);
//         //         $image->setPath($fileName);
//         //     }
//         // }
//         $entityManager->persist($trick);
//         $entityManager->flush();

//         $this->addFlash('success', 'Le trick a bien été ajouté !');

//         return $this->redirectToRoute('app_home');
//     }

//     return $this->render('tricks/create.html.twig', [
//         'form' => $form->createView(),
//     ]);
// }

    
//}
