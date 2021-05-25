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
     * @Route("/cv/downloaad")
     * @return BinaryFileResponse
     */
    public function cv($projectDirectory)
    {
        //$file = new File($this->get('kernel')->getProjectDir().'/public/cvPicassoHouessou.pdf')  ;
        $file = new File($projectDirectory.'/public/cvPicassoHouessou.pdf')  ;

        // rename the downloaded file
        return $this->file($file, 'CV de Picasso Houessou.pdf');

        //return new BinaryFileResponse($file);
    }
}