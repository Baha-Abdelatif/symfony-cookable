<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class, [
                'attr'=> [
                    'minlength'=>'2',
                    'maxlength'=>'180'
                ],
                'label'=>"Votre adresse mail",
                "constraints"=>[
                    new NotBlank([
                        'message'=>"L'adresse mail est obligatoire"
                    ]),
                    new Length([
                        'min'=>2,
                        'max'=>180,
                        'minMessage'=>"L'adresse mail doit contenir au moins {{ limit }} caractères",
                        'maxMessage'=>"L'adresse mail ne peut pas contenir plus de {{ limit }} caractères"
                    ]),
                    new Email([
                        'message'=>"L'adresse mail n'est pas valide"
                    ])
                ]

            ])
            ->add('fullName', TextType::class, [
                'attr'=> [
                    'minlength'=>'2',
                    'maxlength'=>'50'
                ],
                'label'=>"Votre nom complet",
                "constraints"=>[
                    new NotBlank([
                        'message'=>"Le nom complet est obligatoire"
                    ]),
                    new Length([
                        'min'=>2,
                        'max'=>50,
                        'minMessage'=>"Le nom complet doit contenir au moins {{ limit }} caractères",
                        'maxMessage'=>"Le nom complet ne peut pas contenir plus de {{ limit }} caractères"
                    ])
                ]
            ])
            ->add('username', TextType::class, [
                'attr'=> [
                    'minlength'=>'2',
                    'maxlength'=>'50'
                ],
                'label'=>"Votre nom d'utilisateur",
                'required'=>false,
                "constraints"=>[
                    new Length([
                        'min'=>2,
                        'max'=>50,
                        'minMessage'=>"Le nom d'utilisateur doit contenir au moins {{ limit }} caractères",
                        'maxMessage'=>"Le nom d'utilisateur ne peut pas contenir plus de {{ limit }} caractères"
                    ])
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                "type"=>PasswordType::class,
                "first_options"=>[
                    'attr'=> [
                            'minlength'=>'8',
                        'maxlength'=>'255'
                    ],
                    'label'=>"Votre mot de passe",
                    "constraints"=>[
                        new NotBlank([
                            'message'=>"Le mot de passe est obligatoire"
                        ]),
                        new Length([
                            'min'=>8,
                            'max'=>255,
                            'minMessage'=>"Le mot de passe doit contenir au moins {{ limit }} caractères",
                            'maxMessage'=>"Le mot de passe ne peut pas contenir plus de {{ limit }} caractères"
                        ])
                    ]
                ],
                "second_options"=>[
                    'attr'=> [
                            'minlength'=>'8',
                        'maxlength'=>'255'
                    ],
                    'label'=>"Confirmez votre mot de passe",
                    "constraints"=>[
                        new NotBlank([
                            'message'=>"La confirmation du mot de passe est obligatoire"
                        ]),
                        new Length([
                            'min'=>8,
                            'max'=>255,
                            'minMessage'=>"La confirmation du mot de passe doit contenir au moins {{ limit }} caractères",
                            'maxMessage'=>"La confirmation du mot de passe ne peut pas contenir plus de {{ limit }} caractères"
                        ])
                    ]
                ],
                'invalid_message' => 'Les mots de passe doivent être identiques',
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => [
                    'class' => 'btn btn-primary'
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
