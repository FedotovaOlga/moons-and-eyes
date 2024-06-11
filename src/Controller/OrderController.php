<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }
        $products = $cart->getCart();
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Store information in database

            // Creation of the address string
            $addressObj = $form->get('addresses')->getData();
            $address = $addressObj->getFirstname().' '.$addressObj->getLastname().'<br>';
            $address .= $addressObj->getAddress().'<br>';
            $address .= $addressObj->getPostal().' '.$addressObj->getCity().'<br>';
            $address .= $addressObj->getCountry().'<br>';
            $address .= $addressObj->getPhone();

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setState(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($address);
        };

        // Insertion of the order details in the order
        foreach ($products as $product) {
            $orderDetail = new OrderDetail();
            $orderDetail->setProductName($product['object']->getName());
            $orderDetail->setProductIllustration($product['object']->getIllustration());
            $orderDetail->setProductPrice($product['object']->getPrice());
            $orderDetail->setProductVat($product['object']->getVat());
            $orderDetail->setProductQuantity($product['quantity']);
            $order->addOrderDetail($orderDetail); // Add the order detail to the order
        };

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart'=> $products,
            'totalWt' => $cart->getTotalWt(),
        ]);
    }
}
