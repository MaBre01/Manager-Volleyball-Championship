<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Club;
use App\Entity\Team;
use App\Entity\TeamManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query\Expr\Math;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $nbClub = 20;

        for ($i = 0; $i < $nbClub; $i++){
            $club = new Club(0, $faker->city);

            for( $i = 0; $i < random_int(1,4); $i++){

                $teamManager = new TeamManager($faker->firstName, $faker->lastName, $faker->phoneNumber);

                $email = $teamManager->getFirstName() . "." . $teamManager->getLastName() . "@gmail.com";
                $account = new Account($email, "123456", ['ROLE_TEAM']);

                $manager->persist($account);

                $teamName = $club->getName() . "--" . $i;
                $team = new Team(0, $teamName, $club, true, $account , $teamManager);

                $manager->persist( $team );
            }

            $manager->persist( $club );
        }

        /* Add admin account */
        $admin = new Account("admin@gmail.com", "123456", ['ROLE_ADMIN']);
        $manager->persist( $admin );

        $manager->flush();
    }
}
