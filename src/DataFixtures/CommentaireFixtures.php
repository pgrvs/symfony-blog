<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Auteur;
use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentaireFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        for ($i=0;$i<20;$i++){
            $commentaire = new Commentaire();
            $commentaire->setContenu($faker->paragraphs(1,true))
                        ->setCreatedAt($faker->dateTimeBetween('-6months'));

            $numAuteur = $faker->numberBetween(0,10);
            $commentaire->setAuteur($this->getReference("auteur".$numAuteur));
            $numArticle = $faker->numberBetween(0,100);
            $commentaire->setArticle($this->getReference("article".$numArticle));


            $manager->persist($commentaire);
        }

        for ($i=0;$i<30;$i++){
            $commentaire = new Commentaire();
            $commentaire->setContenu($faker->paragraphs(1,true))
                ->setCreatedAt($faker->dateTimeBetween('-6months'));

            $numArticle = $faker->numberBetween(0,99);
            $commentaire->setArticle($this->getReference("article".$numArticle));


            $manager->persist($commentaire);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return[
            AuteurFixtures::class,
            ArticleFixtures::class
        ];
    }
}
