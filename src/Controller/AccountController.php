<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController

{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/account.html.twig');
    }
// Route for modifying of the password :

    #[Route('/account/modify-password', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher): Response // inject dependency (object) Request that we put into variable $request
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
        return $this->render('account/password.html.twig', [
            'modifyPwd' => $form->createView() // Pass the form to the template twig (by passing the object form with createView method to the variable 'modifyPwd')
        ]);
    }

    #[Route('/account/addresses', name: 'app_account_addresses')]
    public function addresses(): Response
    {
        return $this->render('account/addresses.html.twig');
    }

    #[Route('/account/address/delete/{id}', name: 'app_account_address_delete')]
    public function addressDelete($id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id); // Get the address by id
        if(!$address OR $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_account_addresses');
        }
        $this->addFlash(
            'success',
            "Your address has been deleted successfully!"
        ); // Add a flash message
        $this->entityManager->remove($address); // Remove the address from the database
        $this->entityManager->flush(); // Save the changes to the database
        return $this->redirectToRoute('app_account_addresses');
    }

    #[Route('/account/address/add/{id}', name: 'app_account_address_form', defaults: ['id' => null])]
    public function addressForm(Request $request, $id, AddressRepository $addressRepository): Response
    {
        if ($id) {
            $address = $addressRepository->findOneById($id); // Get the address by id
            if(!$address OR $address->getUser() != $this->getUser()){
                return $this->redirectToRoute('app_account_addresses');
            }
        } else {
            $address = new Address(); // Create a new Address object
            $address->setUser($this->getUser()); // Set the user of the address to the current user
        }

        $form = $this->createForm(AddressUserType::class, $address); // Create the form with the AddressUserType form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $address = $form->getData(); // Get the address data from the form
            $this->entityManager->persist($address); // Persist the address to the database (case of creation of a new object = address)
            $this->entityManager->flush(); // Save the address to the database
            $this->addFlash(
                'success',
                "Your address has been saved successfully!"
            ); // Add a flash message
            return $this->redirectToRoute('app_account_addresses'); // Redirect to the addresses page
        }

        return $this->render('account/addressForm.html.twig', [
            'addressForm' => $form->createView()
        ]);
    }
}
