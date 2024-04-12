<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    // The entity that this CRUD controller manages
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Category') // The name of the entity in singular
            ->setEntityLabelInPlural('Categories') // The name of the entity in plural
        ;
    }

    // Fields to be displayed on the CRUD
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Category name')->setHelp('The name of the category'),
            SlugField::new('slug')->setLabel('URL')->setTargetFieldName('name')->setHelp('The URL of your category generated automatically'), // The URL of the category
        ];
    }

}
