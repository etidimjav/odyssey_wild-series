<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()  
    {
        return [ProgramFixtures::class];  
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');

        // on créé 50 personnes
        for ($i = 0; $i < 50; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $manager->persist($actor);
            $actor->addProgram($this->getReference('program_'.random_int(0,5)));
        }
        $manager->flush();
    }
}
