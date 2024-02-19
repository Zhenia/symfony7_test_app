<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'admin_articles', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articleList = $articleRepository->findBy(['deletedAt' => null]);

        return $this->render('admin_article/index.html.twig', [
            'articleList' => $articleList,
        ]);
    }

    #[Route('/new', name: 'admin_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($this->getUser());
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($article->getDeletedAt()) {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/batch', name: 'admin_article_batch', methods: ['POST'])]
    public function batch(Request $request, ArticleRepository $articleRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $idArticleList = $request->get('article_list', []);
        if (!empty($idArticleList)) {
            $articleList = $articleRepository->findBy(['id' => array_keys($idArticleList)]);
            foreach ($articleList as $article) {
                $article->setDeletedAt(new \DateTime());
                $em->persist($article);
            }

            $em->flush();
        }

        return $this->redirectToRoute('admin_articles', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete', name: 'admin_article_delete', methods: ['POST', 'GET'])]
    public function delete(Article $article, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $article->setDeletedAt(new \DateTime());
        $entityManager->persist($article);
        $entityManager->flush();

        return $this->redirectToRoute('admin_articles', [], Response::HTTP_SEE_OTHER);
    }
}
