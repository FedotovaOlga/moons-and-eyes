<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PasswordUserType extends AbstractType
{
    private $passwordEncoder;
    private $tokenStorage;
    public function __construct(
        UserPasswordHasherInterface $passwordEncoder,
        TokenStorageInterface $tokenStorage
        )
    {
        $this->tokenStorage = $tokenStorage;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => "Your actual password",
                'attr' => [
                    'placeholder' => 'Please enter your actual password'
                ],
                'mapped' => false
            ])
            ->add('password', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'constraints' => [new Length([
                    'min' => 4, 
                    'max' => 30
                ])],
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
                    'class' => 'btn btn-success mt-1'
                ]
            ])
            // Add a listener to check if the actual password is correct.
            // 1st parameter : when do I listen?
            // 2nd parameter : what do I do?
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm(); // Get the form
                $user = ($form->getConfig()->getOptions() ['data']); // Get the user data
                $passwordHasher = $form->getConfig()->getOptions() ['passwordHasher']; // Get the password hasher
                // Get the actual password entered by the user and compare it to the password from the database:
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );
                // If the password is not valid, add an error to the form:
                if (!$isValid) {
                    $form->get('actualPassword')->addError(new FormError("Your actual password is incorrect. Please try again."));
                };
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null 
        ]);
    }
}