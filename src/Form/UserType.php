<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\GroupeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le mot de passe est obligatoire!'
                        ]),
                    ],
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            )
            ->add(
                'groupe',
                EnumType::class,
                [
                    'class' => GroupeType::class,
                    'required' => false,
                    'attr' => [
                        'class' => 'form-select'
                    ]

                ]
            );
        if ($options['is_edit']) {
            $builder
                ->add('password', PasswordType::class, [
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false
        ]);
    }
}
