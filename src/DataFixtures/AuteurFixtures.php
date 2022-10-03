<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AuteurFixtures extends Fixture
{
    private SluggerInterface $slugger;

    /**
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        for ($i=0; $i<=8;$i++){
            $auteur = new Auteur();
            /*$auteur->setNom($faker->l);
            $auteur->getPrenom($faker->);
            $auteur->setPseudo($faker->);*/

            $this->addReference("auteur".$i,$auteur);

            $manager->persist($auteur);
        }

        $manager->flush();
    }
}
