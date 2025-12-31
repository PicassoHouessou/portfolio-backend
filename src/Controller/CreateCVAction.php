<?php

namespace App\Controller;

use App\Entity\CV;
use App\Entity\MediaObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class CreateCVAction extends AbstractController
{
    public function __invoke(Request $request): MediaObject
    {
        $uploadedFile = $request->files->get('file');
        $data = $request->request->all();
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $cv = new CV();
        $cv->setFile ($uploadedFile);
        $cv->setLanguage($data["language"]);

        return $cv;
    }
}
