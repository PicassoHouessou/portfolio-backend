<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $tag = new Tag();
         $tag->setName("Passion");
         $manager->persist($tag);
        $manager->flush();
    }
}
