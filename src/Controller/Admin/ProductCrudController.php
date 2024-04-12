<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    // The entity that this CRUD controller manages
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Product') // The name of the entity in singular
            ->setEntityLabelInPlural('Products') // The name of the entity in plural
        ;
    }

    // Fields to be displayed on the CRUD
    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name')->setLabel('Product Name')->setHelp('The name of your product'), // The name of the product
            SlugField::new('slug')->setTargetFieldName('name')->setLabel('URL')->setHelp('The URL of your product generated automatically'), // The URL of the product
            TextEditorField::new('description')->setLabel('Product Description')->setHelp('The description of your product'), // The description of the product
            ImageField::new('illustration')->setLabel('Image')->setHelp('The image of your product of 600x600px')->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')->setBasePath('/uploads')->setUploadDir('/public/uploads')->setRequired(false), // The image of the product ; required false for the modification
            NumberField::new('price')->setLabel('Price W.T.')->setHelp('The price of your product W.T. without the € symbol'), // The price of the product
    
            ChoiceField::new('vat')->setLabel('VAT rate')->setChoices([
                '0%' => 0,
                '10%' => 10,
                '17%' => 17,
                '20%' => 20,
            ]),
            AssociationField::new('category', 'Associated Category') // The category of the product
        ];
    }

}
