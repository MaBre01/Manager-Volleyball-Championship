<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EditTeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => [
                        new Length(['min' => 3, 'max' => 255])
                    ]
                ]
            )
            ->add(
                'email',
                EmailType::class
            )
            ->add(
                'managerLastName',
                TextType::class,
                [
                    'constraints' => [
                        new Length(['max' => 255])
                    ]
                ]
            )
            ->add(
                'managerFirstName',
                TextType::class,
                [
                    'constraints' => [
                        new Length(['max' => 255])
                    ]
                ]
            )
            ->add(
                'phoneNumber',
                TextType::class,
                [
                    'constraints' => [
                        new Length(10)
                    ]
                ]
            )
            ->add(
                'active',
                ChoiceType::class,
                [
                    'choices' => [
                        'Oui' => true, 'Non' => false
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EditTeam::class
        ]);
    }

}