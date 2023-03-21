<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CVController extends AbstractController
{

    /**
     * @Route("/cv/download")
     * @return BinaryFileResponse
     */
    public function cv($projectDirectory, Request $request)
    {
        //$file = new File($this->get('kernel')->getProjectDir().'/public/cvPicassoHouessou.pdf')  ;
        $lang = $request->get('lang','fr');
        $file = "";
        if ($lang== "fr") {
            $file = new File($projectDirectory . '/public/cvPicassoHouessou-fr.pdf');
        } else{

            $file = new File($projectDirectory . '/public/cvPicassoHouessou-en.pdf');

        }
        // rename the downloaded file
        return $this->file($file, 'CV-Picasso-Houessou.pdf');

        //return new BinaryFileResponse($file);
    }
}