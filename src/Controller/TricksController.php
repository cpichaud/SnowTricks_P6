<?php

namespace App\Controller;

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

        return $this->render('tricks\detail_tricks.html.twig', [
            'detailTrick' => $detailTrick,
        ]);
    }

    #[Route('/edit/{tricks}', name: '_profiler_edit_trick')]
    public function editTrick(TrickRepository $trick, $tricks, Request $request): Response
    {
        $editTrick = $trick->findOneBy(array('id' => $tricks));

        $form = $this->createForm(TrickType::class, $tricks);
        //$form->handleRequest($request);

        
       // $pictures = $trick->getPictures();

        return $this->render('tricks\edit_tricks.html.twig', [
            'editTrick' => $editTrick,
            'form' => $form->createView(),
        ]);
    }
}
