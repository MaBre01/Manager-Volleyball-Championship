<?php

namespace App\DataFixtures;

use App\Entity\Club;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $nbClub = 20;

        for ($i = 0; $i < $nbClub; $i++){
            $club = new Club(0, $faker->city);

            $manager->persist( $club );
        }

        $manager->flush();
    }
}
