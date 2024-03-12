<?php

namespace App\DataFixtures\Provider;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaProvider
{
    public static $parameterBag;
    public static $filesystem;

    public function __construct(ParameterBagInterface $parameterBag, Filesystem $filesystem,)
    {

        self::$parameterBag = $parameterBag;
        self::$filesystem = $filesystem;

    }

    public static function media($str)
    {
        $projectDir = self::$parameterBag->get('kernel.project_dir');
        $tmpDirectory = Path::normalize($projectDir . '/var/tmp/images/');

        if (!self::$filesystem->exists($tmpDirectory)) {
            self::$filesystem->mkdir($tmpDirectory);
        }

        $fileName = Path::normalize($tmpDirectory . $str);
        self::$filesystem->copy(Path::normalize($projectDir . '/src/DataFixtures/images/' . $str), $fileName);

        $filePostBlog = new UploadedFile($fileName, $str, 'image/jpg', null, true);
        return $filePostBlog;

    }
}