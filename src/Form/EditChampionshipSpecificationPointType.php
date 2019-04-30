<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditChampionshipSpecificationPointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'winPoint',
                IntegerType::class
            )
            ->add(
                'winWithBonusPoint',
                IntegerType::class
            )
            ->add(
                'loosePoint',
                IntegerType::class
            )
            ->add(
                'looseWithBonusPoint',
                IntegerType::class
            )
            ->add(
                'forfeitPoint',
                IntegerType::class
            )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EditChampionshipSpecificationPoint::class
        ]);
    }
}