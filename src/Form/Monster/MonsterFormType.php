<?php

namespace App\Form\Monster;

use App\Entity\Category;
use App\Entity\Monster;
use App\Repository\MonsterRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MonsterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tu dois entrer un nom pour cet animal'
                    ])
                ]
            ])
            ->add('gender', TextType::class, [
                'label' => 'Genre',
                'required' => false
            ])
            ->add('nicknames', TextareaType::class, [
                'label' => 'Surnoms',
                'required' => false
            ])
            ->add('parents', EntityType::class, [
                'label' => 'Ses parents',
                'required' => false,
                'class' => Monster::class,
                'query_builder' => function (MonsterRepository $monsterRepository) {
                    return $monsterRepository->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC');
                },
                'choice_label' => 'name', // Entity property name
                'expanded' => false,
                'multiple' => true,
            ])
            ->add('birthDay', DateTimeType::class, [
                'label' => 'Date de naissance',
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('arrivalDate', DateTimeType::class, [
                'label' => 'Date d\'arrivée',
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('leavingDate', DateTimeType::class, [
                'label' => 'Date de départ',
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('reasonForLeaving', TextType::class, [
                'label' => 'Raison du départ',
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tu dois choisir une catégorie'
                    ])
                ],
                'class' => Category::class,
                'choice_label' => 'name',
//                'disabled' => true, // already completed, will stay the same even if user tries to change it
                'empty_data' => '',
                'placeholder' => 'Choisis une catégorie'
            ])
            ->add('cossard', CheckboxType::class, [
                'label' => 'Cossard',
                'required' => false
            ])
            ->add('explanatoryText', HiddenType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'fill-me'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data-class' => Monster::class
        ]);
    }
}
