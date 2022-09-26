<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    // A l'appel de la méthode getArticles symfony va créer un
    // objet de la classe ArticleRepository et passer en paramètre de la méthode
    // Mécanisme : INJECTION DE DEPENDANCES
    public function getArticles(ArticleRepository $repository): Response
    {
        // Récupérer les information dans la base de données
        // Le contrôleur fait appel au modèle (classe du modèle)
        // afin de récupérer la liste des articles

        $articles = $repository->findBy([],['createdAt' => 'DESC'],5);

        return $this->render('article/index.html.twig',[
            "articles" => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_id')]
    public function getArticle(ArticleRepository $repository, $id): Response
    {
        $article = $repository->find($id);

        return $this->render('article/article-id.html.twig',[
            "article" => $article
        ]);
    }
}
