<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'app_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBySlug($slug); // connect to the database and get the category with the given slug
        if (!$category) {
            return $this-> redirectToRoute('app_home'); // if the category does not exist, redirect to the home page
        }      
        return $this->render('category/category.html.twig', [
            'category' => $category,
        ]); // render the category template and pass the category object to it
    }
}
