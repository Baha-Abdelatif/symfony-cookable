<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'col-8',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'mt-4 col-4 text-start'
                ],
                'required'=>true,
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('time',IntegerType::class,[
                'attr'=> [
                    'class'=>'col-8',
                    'min'=>1,
                    'max'=>1440
                ],
                'label'=>'Temps de préparation (minutes)',
                'label_attr'=>[
                    'class'=>'mt-4 text-start'
                ],
                'required'=>false,
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThanOrEqual(['value'=>1440])
                ]
            ])
            ->add('people',IntegerType::class,[
                'attr'=> [
                    'class'=>'col-8',
                    'min'=>1,
                    'max'=>50
                ],
                'label'=>'Nombre de personnes',
                'label_attr'=>[
                    'class'=>'mt-4 col-4 text-start'
                ],
                'required'=>false,
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThanOrEqual(['value'=>50])
                ]
            ])
            ->add('difficulty',RangeType::class,[
                'attr'=> [
                    'class'=>'col-8',
                    'min'=>1,
                    'max'=>5
                ],
                'label'=>'Difficulté /5',
                'label_attr'=>[
                    'class'=>'mt-4 col-4 text-start'
                ],
                'required'=>false,
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThanOrEqual(['value'=>5])
                ]
            ])
            ->add('description',TextareaType::class,[
                'attr'=> [
                    'class'=>'col-8 mb-4',
                    'minlength'=>10,
                    'maxlength'=>1500
                ],
                'label'=>'Description',
                'label_attr'=>[
                    'class'=>'mt-4 col-4 text-start'
                ],
                'required'=>true,
                'constraints'=>[
                    new Assert\Length(['min'=>10,'max'=>1500]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('isFavorite',CheckboxType::class,[
                'label'=>'Favoris',
                'required'=>false
            ])
            ->add('price',MoneyType::class,[
                'attr'=> [
                    'class'=>'col-8',
                    'min'=>1,
                    'max'=>1000
                ],
                'label'=>'Prix €',
                'currency'=>'',
                'label_attr'=>[
                    'class'=>'mt-4 text-start'
                ],
                'required'=>false,
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThanOrEqual(['value'=>1000])
                ]
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
