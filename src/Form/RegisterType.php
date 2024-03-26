<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'First Name',
                'constraints' => [new Length(
                    [
                        'min' => 2,
                        'max' => 30
                    ] // Add a constraint to check the length of the first name
                )],
                'attr' => [
                    'placeholder' => 'Please enter your first name'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last Name',
                'constraints' => [new Length(
                    [
                        'min' => 2,
                        'max' => 30
                    ] // Add a constraint to check the length of the last name
                )],
                'attr' => [
                    'placeholder' => 'Please enter your last name'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Please enter your email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'label' => 'Your Password',
                'required' => true,
                'constraints' => [new Length( 
                    [
                        'min' => 6, 
                        'max' => 30
                    ] // Add a constraint to check the length of the password
                )],
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'placeholder' => 'Please choose your password'
                    ],
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => [
                        'placeholder' => 'Please confirm your password'
                    ],
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Register'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([ 
                    'entityClass' => User::class, 
                    'fields' => 'email' // Check if the email is unique
                ])
            ],
            'data_class' => User::class, 
        ]);
    }
}
