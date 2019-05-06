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
        $password = password_hash('a', PASSWORD_BCRYPT);

        for ($i = 0; $i < $nbClub; $i++){
            $club = new Club(0, $faker->city);

            for( $j = 0; $j < random_int(1,4); $j++){

                $teamManager = new TeamManager($faker->firstName, $faker->lastName, $faker->phoneNumber);

                $teamName = $club->getName() . '--' . $j;
                $team = new Team(0, $teamName, $club, true, $teamManager);

                $manager->persist($team);

                $email = $teamManager->getFirstName() . '.' . $teamManager->getLastName() . '@gmail.com';
                $account = new Account($email, $password, ['ROLE_TEAM'], $team);

                $manager->persist($account);
            }

            $manager->persist( $club );
        }

        /* Add admin account */
        $admin = new Account('admin@gmail.com', $password, ['ROLE_ADMIN'], null);
        $manager->persist( $admin );

        $manager->flush();
    }
}
