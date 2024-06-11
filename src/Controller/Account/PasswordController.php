<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PasswordUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;


class PasswordController extends AbstractController

{

// Dependency injection (object) EntityManagerInterface that permit to interact with the database :

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

// Route for modifying of the password :

    #[Route('/account/modify-password', name: 'app_account_modify_pwd')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response // inject dependency (object) that we put into variable 
    {
        $user = $this->getUser(); // Get the current user
        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]); 
        // Create the form with the PasswordUserType form and the current user object.
        // 1 : path of my class Form (PasswordUserType)
        // 2 : the object user 
        // 3 : an array that contains the password hasher interface.

        $form->handleRequest($request); // Listen and handle the request

        if ($form->isSubmitted() && $form->isValid()) {
            $passHash = $form->get('password')->getData(); // Get the password from the form
            $newPassword = $passwordHasher->hashPassword($user, $passHash); // Hash the password
            $user->setPassword($newPassword); // Set the new password
            $this->entityManager->flush(); // Save the user to the database
            $this->addFlash(
                'success',
                "Your password has been modified successfully!"
            );
        }
        return $this->render('account/password/password.html.twig', [
            'modifyPwd' => $form->createView() // Pass the form to the template twig (by passing the object form with createView method to the variable 'modifyPwd')
        ]);
    }
}
