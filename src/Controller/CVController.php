<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CVController extends AbstractController
{
    #[Route("/cv/download")]
    public function cv($projectDirectory, Request $request): BinaryFileResponse
    {
        $lang = $request->get('lang', 'fr');
        $file = "";
        if ($lang == "fr") {
            $file = new File($projectDirectory . '/public/cvPicassoHouessou-fr.pdf');
        } else {

            $file = new File($projectDirectory . '/public/cvPicassoHouessou-en.pdf');
        }
        return $this->file($file, 'CV-Picasso-Houessou.pdf');
    }
}