<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $postCategory = new Category();
        $postCategory
            //->setCreatedAt()
            ->setName("test");
        //->setUpdatedAt();
        $manager->persist($postCategory);

        $manager->flush();
    }
}
