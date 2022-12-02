<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => false,
                'constraints' => [
                    new Length([
                        'min' => '2',
                        'minMessage' => 'Your name should be at least 2 characters'
                    ]),
                    new NotBlank([
                        'message' => 'Please enter a First name',
                    ]),
                ], 
                'attr' => [
                    'autocomplete' => 'firstname',                     
                    'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
                    'placeholder' => 'Firstname'
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'lastname',                     
                    'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
                    'placeholder' => 'Lastname'
                ],
                'constraints' => [
                    new Length([
                        'min' => '2',
                        'minMessage' => 'Your lastname should be at least 2 characters'
                    ]),
                    new NotBlank([
                        'message' => 'Please enter a last name',
                    ]),
                ]
            ])
            ->add('email', TextType::class, [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'email',                     
                    'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
                    'placeholder' => 'Email'
                ],
                'constraints' => [
                    new Email([
                        'message' => 'This email is not valid email.'
                    ]),
                    new NotBlank([
                        'message' => 'Please enter a email',
                    ]),
                ],
            ])
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'username',                     
                    'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
                    'placeholder' => 'Username'
                ],
                'constraints' => [
                    new Length([
                        'min' => '2',
                        'minMessage' => 'Your name should be at least 2 characters'
                    ]),
                    new NotBlank([
                        'message' => 'Please enter a username',
                    ]),
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'attr' => [                    
                    'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-5 text-2xl outline-none',
                ],                
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'The passwords fields must match.',
                'required' => true,
                'mapped' => false,
                'options' => [
                    'mapped' => false,
                    'label' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new Regex([
                            'pattern' => '/\d+/i',
                            'message' => 'Your password shoud contain at least 1 number',
                        ]),
                    ],
                ],
                'first_options' => [
                    'attr' => [
                        'placeholder' => 'Password',
                        'autocomplete' => 'new-password',
                        'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'Repeat password',
                        'autocomplete' => 'new-password',
                        'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
                    ],
                ],
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
