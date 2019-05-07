<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditGameDayDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gameDays = $options["gameDays"];

        foreach ( $gameDays as $gameDay ){

            $builder->add(
                "beginningGameDay",
                CollectionType::class,
                [
                    "entry_type" => DateType::class,
                    "entry_options" => [
                        "widget" => "single_text"
                    ]
                ]
            );

            $builder->add(
                "endingGameDay",
                CollectionType::class,
                [
                    "entry_type" => DateType::class,
                    "entry_options" => [
                        "widget" => "single_text"
                    ]
                ]
            );

        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "gameDays" => []
        ]);
    }
}