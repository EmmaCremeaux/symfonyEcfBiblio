<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test')]

class TestController extends AbstractController
{
    #[Route('/user', name: 'app_test_user')]
    public function user(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $userRepository = $em->getRepository(User::class);

            // Requêtes de lecture :

        // Liste complète de tous les utilisateurs, triée par ordre alphabétique d'email
        $userList = $userRepository->findAll();

        // Données de l'utilisateur dont l'id est `1`
        $user = $userRepository->find(1);

        // Données de l'utilisateur dont l'email est `foo.foo@example.com`
        $foo = $userRepository->findOneByEmail('foo.foo@example.com');

        // Liste des utilisateurs dont l'attribut `roles` contient le mot clé `ROLE_USER`, triée par ordre alphabétique d'email
        $userRole = $userRepository->findUsersByRole('user');
        
        // Liste des utilisateurs inactifs, triée par ordre alphabétique d'email
        $userNotEnabled = $userRepository->findUsersByNotEnabled();

        return $this->render('test/user.html.twig', [
            'userList' => $userList,
            'user' => $user,
            'foo' => $foo,
            'userRole' => $userRole,
            'userNotEnabled' => $userNotEnabled,
        ]);
    }

    #[Route('/livre', name: 'app_test_livre')]
    public function livre(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $livreRepository = $em->getRepository(Livre::class);

            // Requêtes de lecture :

        // Liste complète de tous les livres, triée par ordre alphabétique de titre
        $livreList = $livreRepository->findAll();

        // Données du livre dont l'id est `1`
        $livre1 = $livreRepository->find(1);

        // Liste des livres dont le titre contient le mot clé `lorem`, triée par ordre alphabétique de titre
        $loremLivre = $livreRepository->findBookByTitle('lorem');

        // Liste des livres dont l'id de l'auteur est `2`, triée par ordre alphabétique de titre
        $livreAuteur = $livreRepository->findByAuteur(2);

        // Liste des livres dont le genre contient le mot clé `roman`, triée par ordre alphabétique de titre
        $genreRepository = $em->getRepository(Genre::class);
        $genreRoman = $livreRepository->findByGenre('roman');

            // Requêtes de création :
        $auteurRepository = $em->getRepository(Auteur::class);
        $auteurSF = $auteurRepository->find(2);
        $genreSF = $genreRepository->find(6);

            //   - ajouter un nouveau livre
        $sf = new Livre();
        //   - titre : Totum autem id externum
        $sf->setTitre('Totum autem id externum');
        //   - année d'édition : 2020
        $sf->setAnneeEdition(2020);
        //   - nombre de pages : 300
        $sf->setNombrePage(300);
        //   - code ISBN : 9790412882714
        $sf->setCodeIsbn(9790412882714);
        //   - auteur : Hugues Cartier (id `2`)
        $sf->setAuteur($auteurSF);
        //   - genre : science-fiction (id `6`)
        $sf->addGenre($genreSF);

        $em->persist($sf);
        $em->flush();


            // Requêtes de mise à jour :

        $genreRA = $genreRepository->find(5);
        //   - modifier le livre dont l'id est `2`
        $livre2 = $livreRepository->find(2);
        //   - titre : Aperiendum est igitur
        $livre2->setTitre('Aperiendum est igitur');
        //   - genre : roman d'aventure (id `5`)
        $livre2->addGenre($genreRA);
        $em->flush();


            // Requêtes de suppression :
        $livre123 = $livreRepository->find(123);
            //   - supprimer le livre dont l'id est `123`
        if ($livre123){
            // Suppression de l'objet
            $em->remove($livre123);
            $em->flush();
        }

        return $this->render('test/livre.html.twig', [
            'livreList' => $livreList,
            'livre1' => $livre1,
            'loremLivre' => $loremLivre,
            'livreAuteur' => $livreAuteur,
            'genreRoman' => $genreRoman,
            'sf' => $sf,
            'livre2' => $livre2,
        ]);
    }

    #[Route('/emprunteur', name: 'app_test_emprunteur')]
    public function emprunteur(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        

        return $this->render('test/emprunteur.html.twig', [
            
        ]);
    }

//     Requêtes de lecture :

// - la liste complète des emprunteurs, triée par ordre alphabétique de nom et prénom
// - les données de l'emprunteur dont l'id est `3`
// - les données de l'emprunteur qui est relié au user dont l'id est `3`
// - la liste des emprunteurs dont le nom ou le prénom contient le mot clé `foo`, triée par ordre alphabétique de nom et prénom
// - la liste des emprunteurs dont le téléphone contient le mot clé `1234`, triée par ordre alphabétique de nom et prénom
// - la liste des emprunteurs dont la date de création est antérieure au 01/03/2021 exclu (c-à-d strictement plus petit), triée par ordre alphabétique de nom et prénom

}
