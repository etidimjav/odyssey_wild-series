<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()  
    {
        return [SeasonFixtures::class];  
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');

        // on créé 250 épisodes
        for ($i = 0; $i < 250; $i++) {
            $episode = new Episode();
            $episode->setTitle($faker->text($maxNbChars = 50));
            $episode->setSynopsis($faker->text($maxNbChars = 200));
            $episode->setNumber($faker->numberBetween($min = 1, $max = 250));
            $manager->persist($episode);
            $episode->setSeason($this->getReference('season'.random_int(0,49)));
        }
        $manager->flush();
    }
}
