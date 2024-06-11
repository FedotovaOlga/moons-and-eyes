<?php

namespace App\Controller\Account;

use App\Class\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddressUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Address;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;


class AddressController extends AbstractController

{

// Dependency injection (object) EntityManagerInterface that permit to interact with the database :

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
// Route for rendering the addresses page :

    #[Route('/account/addresses', name: 'app_account_addresses')]
    public function index(): Response
    {
        return $this->render('account/address/addresses.html.twig');
    }

// Route for deleting an address :

    #[Route('/account/address/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id, AddressRepository $addressRepository): Response
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

// Route for adding or modifying an address :

    #[Route('/account/address/add/{id}', name: 'app_account_address_form', defaults: ['id' => null])]
    public function form(Request $request, $id, AddressRepository $addressRepository, Cart $cart): Response
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

           if ($cart->fullQuantity() > 0) {
            return $this ->redirectToRoute('app_order'); // Redirect to the order page if there is something in the cart
           }
            return $this->redirectToRoute('app_account_addresses'); // Redirect to the addresses page
        }

        return $this->render('account/address/addressForm.html.twig', [
            'addressForm' => $form->createView()
        ]);
    }

}
