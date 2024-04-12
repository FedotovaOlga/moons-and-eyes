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
        $category = $categoryRepository->findOneBySlug($slug);

        // 1. J'ouvre une connexion avec ma BDD
        // 2. Connecte-toi à la table qui s'appelle Category
        // 3. Fais une action en base de données

        return $this->render('category/category.html.twig', [
            'category' => $category,
        ]);
    }
}
