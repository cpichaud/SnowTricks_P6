<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: '_profiler_home', methods: ['GET'])]
    public function index(TrickRepository $trick): Response
    {
        $tricks = $trick->findBy([], ['date_created' => 'DESC']);
       // $pictures = $trick->getPictures();

        return $this->render('/home.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}