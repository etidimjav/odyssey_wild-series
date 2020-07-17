<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()  
    {
        return [ProgramFixtures::class];  
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');

        // on créé 50 saisons
        for ($i = 0; $i < 50; $i++) {
            $season = new Season();
            $season->setYear($faker->year($max = 'now'));
            $season->setDescription($faker->text($maxNbChars = 200));
            $season->setNumber($faker->numberBetween($min = 1, $max = 50));
            $manager->persist($season);
            $this->addReference('season' . $i, $season);
            $season->setProgram($this->getReference('program_'.random_int(0,5)));
        }
        $manager->flush();
    }
}
