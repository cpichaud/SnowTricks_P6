<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Index function.
     *
     * @param TrickRepository $trick Repository used to fetch tricks
     * @return Response Returns a Response object
     */
    #[Route('/', name: 'app_home')]
    public function index(TrickRepository $trick): Response
    {
        $tricks = $trick->findBy([], ['createdAt' => 'DESC']);

        return $this->render('/home/index.html.twig', ['tricks' => $tricks]);
    }
}
