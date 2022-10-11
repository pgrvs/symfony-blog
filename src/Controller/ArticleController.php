<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Egulias\EmailValidator\Result\Reason\CommentsInIDRight;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private CommentaireRepository $commentaireRepository;
    //Demander a Symfony d'indentifier une instance de ArticleRepository

    /**
     * @param ArticleRepository $articleRepository
     * @param CommentaireRepository $commentaireRepository
     */
    public function __construct(ArticleRepository $articleRepository, CommentaireRepository $commentaireRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->commentaireRepository = $commentaireRepository;
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
            $this->articleRepository->findBy(["publie" => true], ['createdAt' => 'DESC']),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('article/index.html.twig',[
            "articles" => $articles,
        ]);
    }

    #[Route('/articles/{slug}', name: 'app_articles_slug')]
    public function getArticle($slug, Request $request): Response
    {
        // formulaire commentaire :
        $commentaire = new Commentaire();
        $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);

        // Reconnaitre si le formulaire a été soumis ou pas
        $formCommentaire->handleRequest($request);
        // Est-ce que le formulaire a été soumis
        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()){
            $commentaire->setCreatedAt(new \DateTime());
            // Insérer l'article dans la base de données
            $this->commentaireRepository->add($commentaire, true);
            return $this->redirectToRoute("app_articles_slug", ['slug' => $slug]);
        }

        $article = $this->articleRepository->findOneBy(["slug" => $slug, "publie" => true]);
        if ($article) {
            return  $this->renderForm('article/article.html.twig',[
                'formCommentaire'=>$formCommentaire,
                "article" => $article
            ]);
        }


        return $this->redirectToRoute('app_articles');
    }

    #[Route('/articles/nouveau', name: 'app_articles_nouveau', methods: ['GET', 'POST'], priority: 1)]
    public function insert(SluggerInterface $slugger, Request $request) : Response
    {
        $article = new Article();

        // Création du formulair
        $formArticle = $this->createForm(ArticleType::class, $article);

        // Reconnaitre si le formulaire a été soumis ou pas
        $formArticle->handleRequest($request);
        // Est-ce que le formulaire a été soumis
        if ($formArticle->isSubmitted() && $formArticle->isValid()){
            $article->setSlug($slugger->slug($article->getTitre())->lower())
                    ->setCreatedAt(new \DateTime());
            // Insérer l'article dans la base de données
            $this->articleRepository->add($article, true);
            return $this->redirectToRoute("app_articles");
        }

        // Appel de la vue twig permettant d'afficher le formulaire
        return  $this->renderForm('article/nouveau.html.twig',[
            'formArticle'=>$formArticle
        ]);

//        $article->setTitre('Nouvel Article 2')
//                ->setContenu("Contenu du nouvel article 2")
//                ->setSlug($slugger->slug($article->getTitre())->lower())
//                ->setCreatedAt(new \DateTime());
//        // Symfony 6
//        $this->articleRepository->add($article,true);
//
//        return $this->redirectToRoute('app_articles');
    }

    #[Route('/articles/edit/{slug}', name: 'app_articles_edit', methods: ['GET', 'POST'])]
    public function edit($slug, SluggerInterface $slugger, Request $request) : Response
    {
        $article = $this->articleRepository->findOneBy(["slug" => $slug, "publie" => true]);

        // Création du formulair
        $formArticle = $this->createForm(ArticleType::class, $article);

        // Reconnaitre si le formulaire a été soumis ou pas
        $formArticle->handleRequest($request);
        // Est-ce que le formulaire a été soumis
        if ($formArticle->isSubmitted() && $formArticle->isValid()){
            $article->setSlug($slugger->slug($article->getTitre())->lower());
            // Insérer l'article dans la base de données
            $this->articleRepository->add($article, true);
            return $this->redirectToRoute("app_articles_slug", ['slug' => $slug]);
        }

        // Appel de la vue twig permettant d'afficher le formulaire
        return  $this->renderForm('article/edit.html.twig',[
            'formArticle'=>$formArticle
        ]);
    }

}
