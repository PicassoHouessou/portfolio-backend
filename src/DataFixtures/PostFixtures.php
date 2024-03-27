<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Cocur\Slugify\SlugifyInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    protected $slugify;

    public function __construct(SlugifyInterface $slugify)
    {
        $this->slugify = $slugify;


    }

    public function load(ObjectManager $manager)
    {
//        $post = new Post();
//        $post->setAuthor()
//            ->addCategory()
//            ->setCreatedAt()
//            ->setContent()
//            ->setTitle()
//            ->setSlug($this->slugify->slugify($post->getTitle()))
//            ->setIsActivated(true);
//        $manager->persist($post);
//
//        $manager->flush();
    }
}
