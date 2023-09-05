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
}
