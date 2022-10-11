<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Auteur;
use App\Entity\Commentaire;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', CKEditorType::class)
            ->add('auteur', EntityType::class, [
                'class' => Auteur::class,
                'choice_label' => 'pseudo',
                'required' => false,
                'placeholder' => 'anonyme'
            ])
            ->add('article', EntityType::class, [
                'class' => Article::class,
                'choice_label' => 'id',
                'attr' => ['class' => 'visually-hidden'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
