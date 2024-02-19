<?php

namespace App\Controller\Web;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_articles', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articleList = $articleRepository->findBy(['deletedAt' => null], ['updatedAt' => 'asc']);

        return $this->render('article/index.html.twig', [
            'articleList' => $articleList,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}
