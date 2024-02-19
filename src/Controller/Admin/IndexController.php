<?php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class IndexController extends AbstractController
{
    #[Route('/', name: 'admin_index')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articleList = $articleRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'articleList' => $articleList,
        ]);
    }
}
