<?php

namespace App\DataFixtures;

use App\Entity\PostCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $postCategory = new PostCategory();
        $postCategory->setCreatedAt()
            ->setCreatedAt()
            ->setName()
            ->setUpdatedAt();
         $manager->persist($postCategory);

        $manager->flush();
    }
}
