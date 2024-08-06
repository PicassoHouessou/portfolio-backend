<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\ContactUs;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\MailerInterface;

final class ContactUsPostProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface $persistProcessor,
        private readonly ProcessorInterface          $removeProcessor,
        private Security $security,
        private MailerInterface             $mailer,
 private                                    $myEmail, private $noReplyEmail
    )
    {

//        $this->mailer = $mailer;
//        $this->myEmail = $myEmail;
//        $this->noReplyEmail = $noReplyEmail;
    }
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {

        $this->sendWelcomeEmail($data);
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $data;
        }
        return ["success" => true];
    }

    public function sendWelcomeEmail(ContactUs $contactUs)
    {
        $email = (new TemplatedEmail())
            ->from($this->noReplyEmail)
            ->to($this->myEmail)
            ->subject($contactUs->getSubject())
            ->htmlTemplate('email/contact_us.md.twig')
            // pass variables (name => value) to the template
            ->context([
                'contactUs' => $contactUs,
            ]);
        $this->mailer->send($email);
    }
}
