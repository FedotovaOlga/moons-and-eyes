<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'label' => "Your firstname :",
                'attr' => [
                    'placeholder' => "Please enter your firstname..."
                ]
            ])
            ->add('lastname', TextType::class,[
                'label' => "Your lastname :",
                'attr' => [
                    'placeholder' => "Please enter your lastname..."
                ]
            ])
            ->add('address', TextType::class,[
                'label' => "Your address :",
                'attr' => [
                    'placeholder' => "Please enter your address..."
                ]
            ])
            ->add('postal', TextType::class,[
                'label' => "Your ZIP-code :",
                'attr' => [
                    'placeholder' => "Please enter your ZIP-code..."
                ]
            ])
            ->add('city', TextType::class,[
                'label' => "Your city :",
                'attr' => [
                    'placeholder' => "Please enter your city..."
                ]
            ])
            ->add('country', CountryType::class,[
                'label' => "Your country :",
                'attr' => [
                    'placeholder' => "Please enter your country..."
                ]
            ])
            ->add('phone', TextType::class,[
                'label' => "Your phone number :",
                'attr' => [
                    'placeholder' => "Please enter your phone number..."
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save my address',
                'attr' => [
                    'class' => 'btn btn-success mt-1'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
