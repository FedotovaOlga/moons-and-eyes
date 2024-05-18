<?php

namespace App\Controller;

use App\Class\Cart;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /*
    * 1st stage of the sales tunnel :
    * Choice of the delivery address and the carrier
    */
    #[Route('/order/delivery', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();
        if (count($addresses)==0) {
            return $this->redirectToRoute('app_account_address_form');
        }
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary')
        ]);

        return $this->render('order/order.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }

    /*
    * 2nd stage of the sales tunnel :
    * Summary of the order
    * Insertion in the database
    * Preparation of Stripe payment
    */
    #[Route('/order/summary', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            // Store information in database
        }

        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart'=> $cart->getCart(),
            'totalWt' => $cart->getTotalWt(),
        ]);
    }
}
