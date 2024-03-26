<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/account.html.twig');
    }

    #[Route('/account/modify-password', name: 'app_account_modify_pwd')]
    public function password(): Response
    {
        $form = $this->createForm(type: PasswordUserType::class);
        return $this->render('account/password.html.twig', [
            'modifyPwd' => $form->createView() // Pass the form to the template twig (by passing the object form with createView method to the variable 'modifyPwd')
        ]);
    }
}
