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
            $auteur->setNom($this->faker->lastName());

            $auteur->setPrenom($this->faker->firstName());

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
                'description' => null
            ],
            [
                'nom' => 'nouvelle',
                'description' => null
            ],
            [
                'nom' => 'roman historique',
                'description' => null
            ],
            [
                'nom' => 'roman d\'amour',
                'description' => null
            ],
            [
                'nom' => 'roman d\'aventure',
                'description' => null
            ],
            [
                'nom' => 'science-fiction',
                'description' => null
            ],
            [
                'nom' => 'fantasy',
                'description' => null
            ],
            [
                'nom' => 'biographie',
                'description' => null
            ],
            [
                'nom' => 'conte',
                'description' => null
            ],
            [
                'nom' => 'témoignage',
                'description' => null
            ],
            [
                'nom' => 'théâtre',
                'description' => null
            ],
            [
                'nom' => 'essai',
                'description' => null
            ],
            [
                'nom' => 'journal intime',
                'description' => null
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
                'auteur' => $auteurs[0],
                'genres' => $genres[0]
            ],
            [
                'titre' => 'Consectetur adipiscing elit',
                'anneeEdition' => '2011',
                'nombrePages' => '150',
                'codeIsbn' => '9783817260935',
                'auteur' => $auteurs[1],
                'genres' => $genres[1]
            ],
            [
                'titre' => 'Mihi quidem Antiochum',
                'anneeEdition' => '2012',
                'nombrePages' => '200',
                'codeIsbn' => '9782020493727',
                'auteur' => $auteurs[2],
                'genres' => $genres[2]
            ],
            [
                'titre' => 'Quem audis satis belle',
                'anneeEdition' => '2013',
                'nombrePages' => '250',
                'codeIsbn' => '9794059561353',
                'auteur' => $auteurs[3],
                'genres' => $genres[3]
            ],
            
        ];
        
        foreach($datas as $data) {
            $livre = new Livre();
            $livre->setTitre($data['titre']);
            $livre->setAnneeEdition($data['anneeEdition']);
            $livre->setNombrePage($data['nombrePages']);
            $livre->setCodeIsbn($data['codeIsbn']);
            $livre->setAuteur($data['auteur']);
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

            $auteur = $this->faker->randomElement($auteurs);
            $livre->setAuteur($auteur);

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
                'emprunteur' => $emprunteurs[0],
                'livre' => $livres[0]
            ],
            [
                'dateEmprunt' => new DateTime('2020-03-01 10:00:00'),
                'dateRetour' => new DateTime('2020-04-01 10:00:00'),
                'emprunteur' => $emprunteurs[1],
                'livre' => $livres[1]
            ],
            [
                'dateEmprunt' => new DateTime('2020-04-01 10:00:00'),
                'dateRetour' => NULL,
                'emprunteur' => $emprunteurs[2],
                'livre' => $livres[2]
            ],
        ];

        foreach($datas as $data) {
            $emprunt = new Emprunt();
            $emprunt->setDateEmprunt($data['dateEmprunt']);
            $emprunt->setDateRetour($data['dateRetour']);
            $emprunt->setEmprunteur($data['emprunteur']);
            $emprunt->setLivre($data['livre']);

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
            
            $emprunteur = $this->faker->randomElement($emprunteurs);
            $emprunt->setEmprunteur($emprunteur);

            $livre = $this->faker->randomElement($livres);
            $emprunt->setLivre($livre);
            
            $this->manager->persist($emprunt);
        }
        $this->manager->flush();
    }

    public function loadUsers() : void 
    {
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
        
            $emprunteur = new Emprunteur();
            $emprunteur->setNom($data['nom']);
            $emprunteur->setPrenom($data['prenom']);
            $emprunteur->setTelephone($data['telephone']);
            $emprunteur->setUser($user);

            $this->manager->persist($emprunteur);
            
            $this->manager->flush();
        }

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
            $emprunteur->setNom($this->faker->lastName());
            $emprunteur->setPrenom($this->faker->firstName());
            $emprunteur->setTelephone($this->faker->unique()->randomNumber());
            $emprunteur->setUser($user);

            $this->manager->persist($emprunteur);

            $this->manager->flush();
        
        }
    }
}