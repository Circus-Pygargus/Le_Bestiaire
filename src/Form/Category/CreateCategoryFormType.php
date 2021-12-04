<?php

namespace App\Form\Category;

use App\Entity\Category;
use App\Entity\Image;
use App\Repository\ImageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateCategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tu dois entrer un nom pour cette catégorie'
                    ])
                ]
            ])
            ->add('featuredImage', EntityType::class, [
                'label' => 'Image représentative',
                'required' => false,
                'class' => Image::class,
                'query_builder' => function (ImageRepository $imageRepository) {
                    // Returns only images that are not already category's featuredImage
                    return $imageRepository->createQueryBuilder('i')
                        ->leftJoin('i.categoryFeatured', 'cf')
                        ->where('cf.id is null')
                        ->orderBy('i.name', 'ASC')
                    ;
                },
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
                'empty_data' => '',
                'placeholder' => 'Choisis ou pas ;)'
            ])
            ->add('explanatoryText', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data-class' => Category::class
        ]);
    }
}
