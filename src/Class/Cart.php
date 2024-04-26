<?php

namespace App\Class;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{

    public function __construct(private RequestStack $requestStack)
    {
    }

    /*
    * add()
    * Function that serves to add a product to the cart
    */
    public function add($product)
    {
        // call symfony session
        $cart = $this->getCart();

        // add number of products +1
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => $cart[$product->getId()]['quantity'] + 1
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => 1
            ];
        }

        // create Cart session
        $this->requestStack->getSession()->set('cart', $cart);
    }

    /*
    * decrease()
    * Function that serves to delete a quantity of a product from the cart
    */
    public function decrease($id)
    {
        $cart = $this->getCart();
        if ($cart[$id]['quantity'] > 1) {
            $cart[$id]['quantity'] = $cart[$id]['quantity'] - 1;
        } else {
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    /*
    * fullQuantity()
    * Function that returns the total number of products in the cart
    */
    public function fullQuantity()
    {
        $cart = $this->getCart();
        $quantity = 0;

        if (!isset($cart)) {
            return $quantity;
        }
        foreach ($cart as $product) {
            $quantity = $quantity + $product['quantity'];
        }
        return $quantity;
    }

    /*
    * getTotalWt()
    * Function that returns the total price of products in the cart
    */
    public function getTotalWt()
    {
        $cart = $this->getCart();
        $price = 0;
        if (!isset($cart)) {
            return $price;
        }
        foreach ($cart as $product) {
            $price = $price + ($product['object']->getPriceWt() * $product['quantity']);
        }
        return $price;
    }

    /*
    * remove()
    * Function that allows to delete completely the cart
    */
    public function remove()
    {
        return $this->requestStack->getSession()->remove(('cart'));
    }

    /*
    * getCart()
    * Function that returns the cart
    */
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
}
