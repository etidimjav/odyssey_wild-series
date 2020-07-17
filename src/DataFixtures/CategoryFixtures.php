<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Horror',
        'Fantastic',
        'Comedy',
        'Drama'
    ];

    public function load(ObjectManager $manager)
    {
        foreach(self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $this->addReference($categoryName, $category);
        }        
        $manager->flush();
    }
}
