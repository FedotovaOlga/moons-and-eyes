<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Request;

class OrderCrudController extends AbstractCrudController
{

    private $em;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Order') // The name of the entity in singular
            ->setEntityLabelInPlural('Orders') // The name of the entity in plural
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('Details')->linkToCrudAction('show');
        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    // Function to change the state of the order
    public function changeState($order, $state)
    {
        // Change the state of the order
        $order->setState($state);
        $this->em->flush();
        // Add a flash message
        $this->addFlash(('success'), "The order state has been updated.");
    }

    // Function to show the order details
    public function show(AdminContext $context, AdminUrlGenerator $urlGenerator, Request $request)
    {
        // get the URL of the "show" action
        $order = $context->getEntity()->getInstance();
        $url = $urlGenerator->setController(self::class)->setAction('show')->setEntityId($order->getId())->generateUrl();

        // Status change
        if ($request->get('state')) {
            $this->changeState($order, $request->get('state'));
        }
        return $this->render('admin/order.html.twig', [
            'order' => $order, // at the left site is the name of the variable that will be used in the template and at the right side is the value of the variable that we created above
            'current_url' => $url,
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt')->setLabel('Date'),
            NumberField::new('state')->setLabel('State')->setTemplatePath('admin/state.html.twig'),
            AssociationField::new('user')->setLabel('Customer'),
            TextField::new('carrierName')->setLabel('Carrier'),
            NumberField::new('totalVat')->setLabel('Total VAT')->setNumDecimals(2),
            NumberField::new('totalWt')->setLabel('Total With Tax')->setNumDecimals(2),
        ];
    }
}
