<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // Générer un url afin d'accéser au CRUD des articles
        $url = $adminUrlGenerator
                ->setController(ArticleCrudController::class)
                ->generateUrl();
        // Rediriger vers cette url
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // Articles :
        yield MenuItem::section("Articles");
        // Créer un sous-menu pour les articles
        yield MenuItem::subMenu("Action","fas fa-bars")
                ->setSubItems([
                    MenuItem::linkToCrud("Lister articles", "fas fa-list", Article::class)
                        ->setAction(Crud::PAGE_INDEX)
                        ->setDefaultSort(['createdAt' => 'DESC']),
                    MenuItem::linkToCrud("Ajouter article", "fas fa-plus", Article::class)
                        ->setAction(Crud::PAGE_NEW),

                ]);

        // Catégories :
        yield MenuItem::section("Catégories");
        yield MenuItem::subMenu("Action","fas fa-bars")
            ->setSubItems([
                MenuItem::linkToCrud("Lister Catégories", "fas fa-list", Categorie::class)
                    ->setAction(Crud::PAGE_INDEX),
                MenuItem::linkToCrud("Ajouter catégorie", "fas fa-plus", Categorie::class)
                    ->setAction(Crud::PAGE_NEW),

            ]);
    }
}
