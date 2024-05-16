<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController

{
// Route for rendering the account page :
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/account.html.twig');
    }
}
