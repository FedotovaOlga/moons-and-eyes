<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);

        return $this->render('order/order.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }
}
