<?php


// src/Form/TrickType.php

namespace App\Form;

use App\Entity\Trick;
use App\Form\VideoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('mainImage', FileType::class, [
                'label' => 'Main Image',
                'mapped' => false,
                'required' => false, 
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'attr' => [ 
                    'id' => 'trick_images',
                    'class' => 'trick_images',
                ],
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => 'Videos',
                'attr' => ['class' => 'video-collection'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}


// namespace App\Form;

// use App\Entity\Trick;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Form\Extension\Core\Type\FileType;
// use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\Form\Extension\Core\Type\TextareaType;
// use Symfony\Component\Form\Extension\Core\Type\CollectionType;

// class TrickType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options)
//     {
//         $builder
//             ->add('name', TextType::class, [
//                 'label' => 'Name',
//             ])
//             ->add('description', TextareaType::class, [
//                 'label' => 'Description',
//             ])
//             ->add('mainImage', FileType::class, [
//                 'attr' => ['class' => 'form-control'],                
//                 'label' => 'Image principale',
//                 'required' => true,
//                 // 'constraints' => [
//                 //     new Image([
//                 //         'mimeTypes' => [
//                 //             'image/jpeg',
//                 //             'image/png',
//                 //         ],
//                 //         'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image',
//                 //     ])
//                 // ],
//             ])
//             ->add('images', CollectionType::class, [
//                 'entry_type' => ImageType::class,
//                 'allow_add' => true,
//                 'allow_delete' => true,
//                 'prototype' => true,
//                 'prototype_name' => '__image__',
//             ]);
//     }

//     public function configureOptions(OptionsResolver $resolver)
//     {
//         $resolver->setDefaults([
//             'data_class' => Trick::class,
//         ]);
//     }
// }
