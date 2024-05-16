<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Carrier') // The name of the entity in singular
            ->setEntityLabelInPlural('Carriers') // The name of the entity in plural
        ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Carrier Name')->setHelp('The name of your carrier'), // The name of the carrier
            TextareaField::new('description')->setLabel('Carrier Description')->setHelp('The description of your carrier'), // The description of the carrier
            NumberField::new('price')->setLabel('Price W.T.')->setHelp('The price of your carrier W.T. without the € symbol'), // The price of the carrier
        ];
    }

}
