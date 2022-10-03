<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    //Demander a Symfony d'indentifier une instance de ArticleRepository
    /**
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    #[Route('/articles', name: 'app_articles')]
    // A l'appel de la méthode getArticles symfony va créer un
    // objet de la classe ArticleRepository et passer en paramètre de la méthode
    // Mécanisme : INJECTION DE DEPENDANCES
    public function getArticles(PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer les information dans la base de données
        // Le contrôleur fait appel au modèle (classe du modèle)
        // afin de récupérer la liste des articles

        // Mise en place de la pagination
        $articles = $paginator->paginate(
            $this->articleRepository->findBy([],['createdAt' => 'DESC']),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('article/index.html.twig',[
            "articles" => $articles,
        ]);
    }

    #[Route('/article/{slug}', name: 'app_article_slug')]
    public function getArticle($slug): Response
    {
        $article = $this->articleRepository->findOneBy(["slug" => $slug]);

        return $this->render('article/article.html.twig',[
            "article" => $article
        ]);
    }
}
