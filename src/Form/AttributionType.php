<?php

namespace App\Form;

use App\Entity\AttributionPoints;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class AttributionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'user',
                EntityType::class,
                [
                    'class' => User::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->andWhere('u.roles LIKE :role')
                            ->orderBy('u.createdAt', 'ASC')
                            ->setParameter('role', '%ROLE_USER%');
                    },
                    'choice_label' => function ($user) {
                        return $user->getDisplayName();
                    },
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'class' => 'form-select'
                    ]
                ]
            )
            ->add(
                'points',
                IntegerType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            )
            ->add(
                'activeAt',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'input' => 'datetime_immutable',
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => AttributionPoints::class,
                'is_edit' => false
            ]
        );
    }
}
