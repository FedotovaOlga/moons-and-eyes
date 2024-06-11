<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    // The entity that this CRUD controller manages
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User') // The name of the entity in singular
            ->setEntityLabelInPlural('Users') // The name of the entity in plural
        ;
    }


    // Fields to be displayed on the CRUD
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname')->setLabel('User\'s firstname'),
            TextField::new('lastname')->setLabel('User\'s lastname'),
            TextField::new('email')->setLabel('Email')->onlyOnIndex(), // The email of the user, only displayed on the index page, not to modify
            ChoiceField::new('roles')->setLabel('Permissions')->setHelp('You can choose the role of this user')->setChoices([
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
            ])->allowMultipleChoices(), // The roles of the user, can be multiple
        ];
    }

}
