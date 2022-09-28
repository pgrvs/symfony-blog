<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    private CategorieRepository $categorieRepository;
    private ArticleRepository $articleRepository;

    /**
     * @param CategorieRepository $categorieRepository
     * @param ArticleRepository $articleRepository
     */
    public function __construct(CategorieRepository $categorieRepository, ArticleRepository $articleRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->articleRepository = $articleRepository;
    }

    #[Route('/categories', name: 'app_categories')]
    public function getCategories(): Response
    {
        $categories = $this->categorieRepository->findBy([],["titre" => "ASC"]);
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categorie/{slug}', name: 'app_categorie_slug')]
    public function index($slug): Response
    {
        $categorie = $this->categorieRepository->findOneBy(["slug" => $slug]);
        //dd($categorie);
        return $this->render('categorie/categorie.html.twig',[
            "categorie" => $categorie
        ]);
    }
}
