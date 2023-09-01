<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Emprunt;
use App\Entity\User;
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
        $this->loadUsers();
        $this->loadGenres();
        $this->loadLivres();
        $this->loadEmprunts();
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

            $this->manager->persist($auteur);
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
        $auteurRepository = $this->manager->getRepository(Auteur::class);
        $auteurs = $auteurRepository->findAll();
        $genreRepository = $this->manager->getRepository(Genre::class);
        $genres = $genreRepository->findAll();

        // données statiques
        $datas = [
            [
                'titre' => 'Lorem ipsum dolor sit amet',
                'anneeEdition' => '2010',
                'nombrePages' => '100',
                'codeIsbn' => '9785786930024',
                'auteurId' => $auteurs[0],
                'genres' => $genres[0]
            ],
            [
                'titre' => 'Consectetur adipiscing elit',
                'anneeEdition' => '2011',
                'nombrePages' => '150',
                'codeIsbn' => '9783817260935',
                'auteurId' => $auteurs[1],
                'genres' => $genres[1]
            ],
            [
                'titre' => 'Mihi quidem Antiochum',
                'anneeEdition' => '2012',
                'nombrePages' => '200',
                'codeIsbn' => '9782020493727',
                'auteurId' => $auteurs[2],
                'genres' => $genres[2]
            ],
            [
                'titre' => 'Quem audis satis belle',
                'anneeEdition' => '2013',
                'nombrePages' => '250',
                'codeIsbn' => '9794059561353',
                'auteurId' => $auteurs[3],
                'genres' => $genres[3]
            ],
            
        ];
        
        foreach($datas as $data) {
            $livre = new Livre();
            $livre->setTitre($data['titre']);
            $livre->setAnneeEdition($data['anneeEdition']);
            $livre->setNombrePage($data['nombrePages']);
            $livre->setCodeIsbn($data['codeIsbn']);
            $livre->setAuteur($data['auteurId']);
            $livre->addGenre($data['genres']);            
            
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


            $shortListAuteur = $this->faker->randomElements($auteurs);
            foreach ($shortListAuteur as $auteur) {
                $livre->setAuteur($auteur); 
            }

            
            $genresCount = random_int(1, 4);
            $shortListGenres = $this->faker->randomElements($genres, $genresCount);

            foreach ($shortListGenres as $genre) {
                $livre->addGenre($genre); 
            }

            $this->manager->persist($livre);
        }
        $this->manager->flush();
    }

    public function loadEmprunts() : void
    {
        $emprunteurRepository = $this->manager->getRepository(Emprunteur::class);
        $emprunteurs = $emprunteurRepository->findAll();
        $livreRepository = $this->manager->getRepository(Livre::class);
        $livres = $livreRepository->findAll();

        // données statiques
        $datas = [
            [
                'dateEmprunt' => new DateTime('2020-02-01 10:00:00'),
                'dateRetour' => new DateTime('2020-03-01 10:00:00'),
                'emprunteurId' => $emprunteurs[0],
                'livreId' => $livres[0]
            ],
            [
                'dateEmprunt' => new DateTime('2020-03-01 10:00:00'),
                'dateRetour' => new DateTime('2020-04-01 10:00:00'),
                'emprunteurId' => $emprunteurs[1],
                'livreId' => $livres[1]
            ],
            [
                'dateEmprunt' => new DateTime('2020-04-01 10:00:00'),
                'dateRetour' => NULL,
                'emprunteurId' => $emprunteurs[2],
                'livreId' => $livres[2]
            ],
        ];

        foreach($datas as $data) {
            $emprunt = new Emprunt();
            $emprunt->setDateEmprunt($data['dateEmprunt']);
            $emprunt->setDateRetour($data['dateRetour']);
            $emprunt->setEmprunteur($data['emprunteurId']);
            $emprunt->setLivre($data['livreId']);

            $this->manager->persist($emprunt);
        }
        $this->manager->flush();

        // données dynamiques
        for($i = 0; $i < 200; $i++) {
            $emprunt = new Emprunt();
            $dateEmprunt = $this->faker->dateTimeBetween('-12 months', '-6 months');
            $emprunt->setDateEmprunt($dateEmprunt);
            $dateRetour = $this->faker->optional(0.7)->dateTimeBetween('-5 months', '-1 months');
            $emprunt->setDateRetour($dateRetour);
            
            $shortListEmprunteur = $this->faker->randomElements($emprunteurs);
            foreach ($shortListEmprunteur as $emprunteur) {
                $emprunt->setEmprunteur($emprunteur); 
            }

            $shortListLivre = $this->faker->randomElements($livres);
            foreach ($shortListLivre as $livre) {
                $emprunt->setLivre($livre); 
            }
            

            $this->manager->persist($emprunt);
        }
        $this->manager->flush();
    }

    public function loadUsers() : void 
    {
        $repoEmprunteur = $this->manager->getRepository(Emprunteur::class);
        $emprunteurs = $repoEmprunteur->findAll();

        // données statiques
        $datas = [
            [
                'email' => 'foo.foo@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'enabled' => true,
                'nom' => 'foo',
                'prenom' => 'foo',
                'telephone' => '123456789'
                
            ],
            [
                'email' => 'bar.bar@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'enabled' => false,
                'nom' => 'bar',
                'prenom' => 'bar',
                'telephone' => '123456789'
            ],
            [
                'email' => 'baz.baz@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'enabled' => true,
                'nom' => 'baz',
                'prenom' => 'baz',
                'telephone' => '123456789'
            ]
        ];
        foreach ($datas as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setRoles($data['roles']);
            $user->setEnabled($data['enabled']);
        
        
            $this->manager->persist($user); 
        
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
                $user = new User();
                $user->setEmail($this->faker->unique()->safeEmail());
                $password = $this->hasher->hashPassword($user, '123');
                $user->setPassword($password);
                $user->setRoles(['ROLE_USER']);
                $enabled = [true, false];
                $user->setEnabled($this->faker->randomElement($enabled));

                $this->manager->persist($user);

                $emprunteur = new Emprunteur();
                $emprunteur->setNom($this->faker->word(1));
                $emprunteur->setPrenom($this->faker->word(1));
                $emprunteur->setTelephone($this->faker->unique()->randomNumber());
                $emprunteur->setUser($user);

                $this->manager->persist($emprunteur);
            }
            $this->manager->flush();
        }
    }

    
}