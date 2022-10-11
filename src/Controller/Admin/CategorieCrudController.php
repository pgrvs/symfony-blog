<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorieCrudController extends AbstractCrudController
{
    private SluggerInterface $slugger;
    /**
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextField::new('slug')
                ->hideOnForm()
                ->hideOnDetail()
                ->hideOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Vérifier que $entityInstance est bien une instance de la classe Article
        if ( !$entityInstance instanceof Categorie) return;
        $entityInstance->setSlug($this->slugger->slug($entityInstance->getTitre())->lower());
        // Appel à la méthode héritée afin de persister l'entité
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(crud::PAGE_INDEX, Action::NEW,
            function (Action $action){
                $action ->setLabel("Ajouter catégorie")
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

        $actions->remove(crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);

        return $actions;
    }

}
