<?php

namespace App\Controller; // Define the namespace of the controller

use App\Entity\User;
use App\Form\RegisterUserType; // Call the namespace App\Form ans import the RegisterType form
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class RegisterController extends AbstractController
{

    private $entityManager; // Create a private variable to hold the entity manager

    public function __construct(EntityManagerInterface $entityManager) { // Create a constructor to inject the entity manager
        $this->entityManager = $entityManager; // Assign the injected entity manager to the private variable
    }

    #[Route('/register', name: 'app_register')]

    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User(); // Create a new user object
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData(); // Get the user data from the form
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword()); // Hash the password
            $user->setPassword($hashedPassword); // Set the hashed password

            $this->entityManager->persist($user); // Persist the user to the database
            $this->entityManager->flush(); // Save the user to the database

            $this->addFlash(
                'success',
                "Your account has been created successfully! You can login now."
            ); // Add a flash message

            return $this->redirectToRoute('app_login'); // Redirect to the login page
        }
        return $this->render('register/register.html.twig', [
            'registerForm'=>$form->createView()
        ]);
    }
}
