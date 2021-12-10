<?php

namespace App\Form\Image;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Monster;
use App\Repository\CategoryRepository;
use App\Repository\MonsterRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tu dois entrer un nom pour cette image'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le nom de l\'image doit être composé d\'au moins 3 caractères',
                        'max' => 128,
                        'maxMessage' => 'Le nom de l\'image doit être composé au maximum de 128 caractères'
                    ])
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'required' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'Tu dois ajouter une image'
                    ]),
                    new ImageConstraint([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            "image/jpg",
                            "image/jpeg",
                            "image/png"
                        ],
                        'mimeTypesMessage' => 'Seuls les fichiers de type jpg, jpeg et png sont autorisés'
                    ])
                ]
            ])
            ->add('alt', TextType::class, [
                'label' => 'Texte alternatif',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tu dois entrer un texte alternatif pour cette image'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le texte alternatif doit être composé d\'au moins 3 caractères',
                        'max' => 128,
                        'maxMessage' => 'Le texte alternatif doit être composé au maximum de 255 caractères'
                    ])
                ]
            ])
            ->add('categoryFeatured', EntityType::class, [
                'label' => 'Représente la catégorie',
                'required' => false,
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->where('c.featuredImage is null')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('monsterFeatured', EntityType::class, [
                'label' => 'Représente l\'animal',
                'required' => false,
                'class' => Monster::class,
                'query_builder' => function (MonsterRepository $monsterRepository) {
                    return $monsterRepository->createQueryBuilder('m')
                        ->where('m.featuredImage is null')
                        ->addOrderBy('m.category', 'ASC')
                        ->addOrderBy('m.name', 'ASC');
                },
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false
            ])
            ->add('monsters', EntityType::class, [
                'label' => 'Qui est sur cette photo ?',
                'required' => true,
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Tu dois choisir le (ou les) monstre présent sur cette photo'
                    ])
                ],
                'class' => Monster::class,
                'query_builder' => function (MonsterRepository $monsterRepository) {
                    return $monsterRepository->createQueryBuilder('m')
                        ->addOrderBy('m.category', 'ASC')
                        ->addOrderBy('m.name', 'ASC');
                },
                'choice_label' => 'name',
                'by_reference' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data-class' => Image::class
        ]);
    }
}
