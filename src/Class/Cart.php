<?php

namespace App\Class;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{

    public function __construct(private RequestStack $requestStack)
    {
    }
    public function add($product)
    {
        // call symfony session
        $cart = $this->requestStack->getSession()->get('cart');

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

    public function decrease($id)
    {
        $cart = $this->requestStack->getSession()->get('cart');
        if ($cart[$id]['quantity'] > 1) {
            $cart[$id]['quantity'] = $cart[$id]['quantity'] - 1;
        } else {
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function remove()
    {
        return $this->requestStack->getSession()->remove(('cart'));
    }

    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
}
