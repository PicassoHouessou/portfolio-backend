<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ContactUs;

class ContactUsController
{
    public function __construct()
    {

    }

    /* Route(
    *     name="contactus_post_publication",
    *     path="/api/contactus",
    *     methods={"POST"},
    *     defaults={
    *         "_api_resource_class"=ContactUs::class,
    *         "_api_item_operation_name"="post_publication"
    *     }
    * )
     */
    public function __invoke(ContactUs $data): ContactUs
    {
        return $data;
    }
}
