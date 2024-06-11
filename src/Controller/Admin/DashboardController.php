<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')] // The route that will be used to access the admin dashboard
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    // The title that will appear in the top-left corner of the admin dashboard.
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Moons And Eyes');
    }

    // The configureMenuItems() method used to define the items that will appear in the left sidebar of the admin dashboard.
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Products', 'fa-solid fa-gem', Product::class);
        yield MenuItem::linkToCrud('Carriers', 'fa-solid fa-truck', Carrier::class);
        yield MenuItem::linkToCrud('Orders', 'fa-solid fa-cart-shopping', Order::class);
    }
}
