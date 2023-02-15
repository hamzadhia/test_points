<?php

namespace App\Form;

use App\Enum\GroupeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class FilterGroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder            
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
    }
}
