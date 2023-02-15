<?php

namespace App\Form;

use App\Enum\GroupeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class AttributionByGroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'points',
                IntegerType::class,
                [
                    'label' => false,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Ajouter des points!'
                            ]
                        )
                    ],
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            )
            ->add(
                'activeAt',
                DateType::class,
                [
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Ajouter une date!'
                            ]
                        )
                    ],
                    'widget' => 'single_text',
                    'input' => 'datetime_immutable',
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
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Ajouter un groupe!'
                            ]
                        )
                    ],
                    'class' => GroupeType::class,
                    'required' => false,
                    'attr' => [
                        'class' => 'form-select'
                    ]

                ]
            );
    }
}
