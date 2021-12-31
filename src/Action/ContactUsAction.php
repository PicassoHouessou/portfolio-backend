<?php

namespace App\Action;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ContactUs;

class ContactUsAction
{
    protected $contactUs ;

    public function __construct(ContactUs $contactUs)
    {
        $this->contactUs = $contactUs;

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
