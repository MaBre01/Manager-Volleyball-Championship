<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EditPitchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'address',
                TextType::class,
                [
                    'constraints' => [
                        new Length(['min' => 3, 'max' => 255])
                    ]
                ]
            )
            ->add(
                'monday',
                CheckboxType::class, [
                    'required' => false,
                    'label' => 'Lundi'
               ]
            )
            ->add(
                'tuesday',
                CheckboxType::class, [
                    'required' => false,
                    'label' => 'Mardi'
                ]
            )
            ->add(
                'wednesday',
                CheckboxType::class, [
                    'required' => false,
                    'label' => 'Mercredi'
                ]
            )
            ->add(
                'thursday',
                CheckboxType::class, [
                    'required' => false,
                    'label' => 'Jeudi'
                ]
            )
            ->add(
                'friday',
                CheckboxType::class, [
                    'required' => false,
                    'label' => 'Vendredi'
                ]
            )
            ->add(
                'saturday',
                CheckboxType::class, [
                    'required' => false,
                    'label' => 'Samedi'
                ]
            )
            ->add(
                'sunday',
                CheckboxType::class, [
                    'required' => false,
                    'label' => 'Dimanche'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EditPitch::class
        ]);
    }
}