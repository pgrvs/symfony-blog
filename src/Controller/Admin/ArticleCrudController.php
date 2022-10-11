<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleCrudController extends AbstractCrudController
{
    private SluggerInterface $slugger;

    // Injecton du slugger au niveau du constructeur
    /**
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('titre')->setLabel('Titre Article'),
            TextEditorField::new('contenu')->setSortable(false)
                ->hideOnIndex(),
            AssociationField::new('categorie', 'Catégorie')->setRequired(false),
            DateTimeField::new('createdAt','Date de création')
                ->hideOnForm(),
            TextField::new('slug')
                ->hideOnForm()
                ->hideOnDetail()
                ->hideOnIndex(),
            BooleanField::new('publie'),
        ];
    }

    // Redéfinir la méthode persistEntity qui va être appelée lors de la création
    // de l'article en base de données
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Vérifier que $entityInstance est bien une instance de la classe Article
        if ( !$entityInstance instanceof Article) return;
        $entityInstance->setCreatedAt(new \DateTime());
        $entityInstance->setSlug($this->slugger->slug($entityInstance->getTitre())->lower());
        // Appel à la méthode héritée afin de persister l'entité
        parent::persistEntity($entityManager, $entityInstance);

    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setPageTitle(Crud::PAGE_INDEX, 'Liste des articles');
        $crud->setPageTitle(Crud::PAGE_DETAIL, "Détail d'un article");
        $crud->setPageTitle(Crud::PAGE_NEW, "Ajout d'un article");
        $crud->setPageTitle(Crud::PAGE_EDIT, "Modifié un article");
        $crud->setPaginatorPageSize(10);
        $crud->setDefaultSort(['createdAt' => 'DESC']);

        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(crud::PAGE_INDEX, Action::NEW,
            function (Action $action){
            $action ->setLabel("Ajouter article")
                    ->setIcon("fa fa-plus");
            return $action;
            }
        );

        $actions->update(crud::PAGE_NEW, Action::SAVE_AND_RETURN,
            function (Action $action){
                $action ->setLabel("Valider")
                        ->setIcon("fa fa-check");
                return $action;
            }
        );

        $actions->add(crud::PAGE_INDEX, Action::DETAIL);

        $actions->remove(crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters->add("titre")
                ->add("createdAt");
        return $filters;
    }


}
