<?php

namespace App\Twig;

use App\Class\Cart;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{

    private $categoryRepository; // we create a private variable to store the categoryRepository
    private $cart; // we create a private variable to store the cart

    public function __construct(CategoryRepository $categoryRepository, Cart $cart) // we create a constructor to inject the categoryRepository
    {
        $this->categoryRepository = $categoryRepository; // we store the categoryRepository in the private variable
        $this->cart = $cart; // we store the cart in the private variable
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']) // 1st argument : name of filter; 2nd argument : current object concerned by the class (AppExtensions); 3rd argument : name of the function to use on the filter, 
        ];
    }

    public function formatPrice($number)
    {
        return number_format($number, '2', ','). '  €';
    }

    public function getGlobals(): array // this function is used to create global variables that can be used in all templates
    {
        return[
            'allCategories' => $this->categoryRepository->findAll(), // we create a global variable that contains all the categories
            'fullCartQuantity' => $this->cart->fullQuantity() // we create a global variable that contains the total quantity of products in the cart. We use the fullQuantity function from the Cart class
        ];
    }
}