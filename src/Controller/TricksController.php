<?php

namespace App\Controller;

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
        $trickstest = $trick->findOneBy(array('title' => $tricks));
       // $pictures = $trick->getPictures();

        return $this->render('tricks\detail_tricks.html.twig', [
            'trickstest' => $trickstest,
        ]);
    }
}
