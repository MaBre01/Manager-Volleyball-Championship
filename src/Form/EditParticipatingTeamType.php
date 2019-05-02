<?php


namespace App\Form;


use App\Entity\Team;
use App\Repository\DoctrineTeamRepository;
use App\Repository\TeamRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditParticipatingTeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $teams = $options['teams'];

        $builder
            ->add(
            'team',
                EntityType::class,
                [
                    'class' => Team::class,
                    'choices' => $teams,
                    'choice_label' => function (Team $team){
                        return $team->getClub()->getName() . " : " . $team->getName();
                    }
                ]
            )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'teams' => [],
            'data_class' => EditParticipatingTeam::class
        ]);
    }
}