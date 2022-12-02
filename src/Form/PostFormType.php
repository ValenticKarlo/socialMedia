<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => array(
                    'class' => 'bg-transparent block border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder' => 'Enter title...',
                ),
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Title',
                    ]),
                ],
            ]) 
            ->add('imagePath', FileType::class, array(
                'attr' => array(
                    'class' => 'py-10',
                ),
                'label'  => false,
                'mapped' => false,
                'constraints' => [
                    new NotNull([
                        'message' => 'Please insert a image file',
                    ]),
                    new Image([
                    ])
                ],
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
