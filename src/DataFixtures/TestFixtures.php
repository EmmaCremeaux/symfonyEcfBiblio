<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use App\Entity\Livre;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $faker;
    private $hasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadAuteurs();
        $this->loadEmprunteurs();
        $this->loadGenres();
        $this->loadLivres();

    }

    public function loadAuteurs() : void
    {
        // données statiques
        $datas = [
            [
                'nom' => 'Auteur',
                'prenom' => 'inconnu',
            ],
            [
                'nom' => 'Cartier',
                'prenom' => 'Hugues',
            ],
            [
                'nom' => 'Lambert',
                'prenom' => 'Armand',
            ],
            [
                'nom' => 'Moitessier',
                'prenom' => 'Thomas',
            ],
        ];

        // $auteurRepository = $this->manager->getRepository(Livre::class);
        // $livres = $auteurRepository->findAll();


        foreach($datas as $data) {
            $auteur = new Auteur();
            $auteur->setNom($data['nom']);
            $auteur->setPrenom($data['prenom']);

            $this->manager->persist($auteur);
        }
        $this->manager->flush();

        // données dynamiques
        for($i = 0; $i < 500; $i++) {
            $auteur = new Auteur();
            $auteur->setNom($this->faker->word(1));

            $auteur->setPrenom($this->faker->word(1));

            // foreach ($livres as $livre) {
            //     $this->livres->add($livre);
            //     $livre->setAuteur($this); 
            //  }

            $this->manager->persist($auteur);
        }
        $this->manager->flush();
    }

    public function loadEmprunteurs() : void
    {
        // données statiques
        $datas = [
            [
                'nom' => 'foo',
                'prenom' => 'foo',
                'telephone' => '123456789'
            ],
            [
                'nom' => 'bar',
                'prenom' => 'bar',
                'telephone' => '123456789'
            ],
            [
                'nom' => 'baz',
                'prenom' => 'baz',
                'telephone' => '123456789'
            ],
        ];

        foreach($datas as $data) {
            $emprunteur = new Emprunteur();
            $emprunteur->setNom($data['nom']);
            $emprunteur->setPrenom($data['prenom']);
            $emprunteur->setTelephone($data['telephone']);

            $this->manager->persist($emprunteur);
        }
        $this->manager->flush();

        // données dynamiques
        for($i = 0; $i < 100; $i++) {
            $emprunteur = new Emprunteur();
            $emprunteur->setNom($this->faker->word(1));
            $emprunteur->setPrenom($this->faker->word(1));
            $emprunteur->setTelephone($this->faker->unique()->randomNumber());

            $this->manager->persist($emprunteur);
        }
        $this->manager->flush();
    }

    public function loadGenres() : void
    {
        // données statiques
        $datas = [
            [
                'nom' => 'poésie',
                'description' => NULL
            ],
            [
                'nom' => 'nouvelle',
                'description' => NULL
            ],
            [
                'nom' => 'roman historique',
                'description' => NULL
            ],
            [
                'nom' => 'roman d\'amour',
                'description' => NULL
            ],
            [
                'nom' => 'roman d\'aventure',
                'description' => NULL
            ],
            [
                'nom' => 'science-fiction',
                'description' => NULL
            ],
            [
                'nom' => 'fantasy',
                'description' => NULL
            ],
            [
                'nom' => 'biographie',
                'description' => NULL
            ],
            [
                'nom' => 'conte',
                'description' => NULL
            ],
            [
                'nom' => 'témoignage',
                'description' => NULL
            ],
            [
                'nom' => 'théâtre',
                'description' => NULL
            ],
            [
                'nom' => 'essai',
                'description' => NULL
            ],
            [
                'nom' => 'journal intime',
                'description' => NULL
            ],
        ];

        foreach($datas as $data) {
            $genre = new Genre();
            $genre->setNom($data['nom']);
            $genre->setDescription($data['description']);

            $this->manager->persist($genre);
        }
        $this->manager->flush();
    }

    public function loadLivres() : void
    {
        // données statiques
        $datas = [
            [
                'titre' => 'Lorem ipsum dolor sit amet',
                'anneeEdition' => '2010',
                'nombrePages' => '100',
                'codeIsbn' => '9785786930024',
                'auteurId' => 1
            ],
            [
                'titre' => 'Consectetur adipiscing elit',
                'anneeEdition' => '2011',
                'nombrePages' => '150',
                'codeIsbn' => '9783817260935',
                'auteurId' => 2
            ],
            [
                'titre' => 'Mihi quidem Antiochum',
                'anneeEdition' => '2012',
                'nombrePages' => '200',
                'codeIsbn' => '9782020493727',
                'auteurId' => 3
            ],
            [
                'titre' => 'Quem audis satis belle',
                'anneeEdition' => '2013',
                'nombrePages' => '250',
                'codeIsbn' => '9794059561353',
                'auteurId' => 4
            ],
            
        ];
        $livreRepository = $this->manager->getRepository(Auteur::class);
        $auteurs = $livreRepository->findAll();

        foreach($datas as $data) {
            $livre = new Livre();
            $livre->setTitre($data['titre']);
            $livre->setAnneeEdition($data['anneeEdition']);
            $livre->setNombrePage($data['nombrePages']);
            $livre->setCodeIsbn($data['codeIsbn']);
            $livre->getAuteur($data['auteurId']);


            $this->manager->persist($livre);
        }
        $this->manager->flush();

        // données dynamiques
        for($i = 0; $i < 1000; $i++) {
            $livre = new Livre();
            $words = random_int(1, 3);
            $livre->setTitre($this->faker->sentence($words));
            $livre->setAnneeEdition($this->faker->optional($weight = 0.6)->year());
            $livre->setNombrePage($this->faker->randomNumber());
            $livre->setCodeIsbn($this->faker->optional($weight = 0.6)->randomNumber());

            foreach ($auteurs as $auteur) {
                $livre->setAuteur($auteur); 
             }

            $this->manager->persist($livre);
        }
        $this->manager->flush();
    }

    
}