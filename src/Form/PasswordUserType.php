<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('actualPassword', PasswordType::class, [
                'label' => "Your actual password",
                'attr' => [
                    'placeholder' => 'Please enter your actual password'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'constraints' => [new Length( 
                    [
                        'min' => 4, 
                        'max' => 30
                    ] // Add a constraint to check the length of the password
                )],
                'first_options' => [
                    'label' => 'Your new password',
                    'attr' => [
                        'placeholder' => 'Please choose your new password'
                    ],
                ],
                'second_options' => [
                    'label' => 'Repeat your new password',
                    'attr' => [
                        'placeholder' => 'Please confirm your new password'
                    ],
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save new password',
                'attr' => [
                    'class' => 'btn btn-success' // Add a bootstrap class to the submit button
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
