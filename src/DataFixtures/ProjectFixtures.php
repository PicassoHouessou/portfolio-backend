<?php

namespace App\DataFixtures;

use App\Entity\MediaObject;
use App\Entity\Project;
use App\Entity\ProjectCategory;
use Cocur\Slugify\SlugifyInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use JsonMachine\Items;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectFixtures extends Fixture  implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['media'];
    }
    protected $slugify;
    protected $parameterBag;

    public function __construct(SlugifyInterface $slugify, ParameterBagInterface $parameterBag)
    {
        $this->slugify = $slugify;
        $this->parameterBag = $parameterBag;
    }

    public function load(ObjectManager $manager)
    {/*
        $projectDir = $this->parameterBag->get("kernel.project_dir");
        $portfolios = Items::fromFile(Path::normalize($projectDir . "/src/DataFixtures/portfolio.json"));
        foreach ($portfolios as $item) {

            $file = new UploadedFile(Path::normalize($projectDir . '/src/DataFixtures/images/portfolio/' . $item->image), $item->image);
            dump($file);
            $media = $this->createMedia($file);
            $manager->persist($media);
            $manager->flush();
            $project = $this->createProject($item->title, $item->subtitle, $item->url, image: $media, active: $item->active);
            $manager->persist($project);
        }
        */

        $manager->flush();
    }
    public function createMedia(UploadedFile $uploadedFile): MediaObject
    {
        $mediaObject = new MediaObject();
        $mediaObject->file = $uploadedFile;
        return $mediaObject;
    }

    public function createProjectCategory(string $name, ?string $description = null, ?string $slug = null): ProjectCategory
    {
        $projectCategory = new ProjectCategory();
        $projectCategory->setName($name)->setDescription($description)->setSlug($slug ?? $this->slugify->slugify($name));
        return $projectCategory;
    }



    public function createProject(string $title, string $subtitle = null, ?string $url = null, ?ProjectCategory $category = null, ?string $content = null, ?MediaObject $image = null, bool $active = true): Project
    {
        $project = new Project();
        $project->setTitle($title)->setSubtitle($subtitle)->setUrl($url)->setCategory($category)->setContent($content)->setImage($image)->setIsActive($active);
        return $project;
    }
}
