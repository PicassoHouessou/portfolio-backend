<?php

namespace App\DataPersister;

use ApiPlatform\State\ProcessorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\ContactUs;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;

final class ContactUsPostProcessor implements ProcessorInterface
{
    private $mailer;

    private $myEmail;
    private $noReplyEmail;


    public function __construct(MailerInterface $mailer, $myEmail, $noReplyEmail)
    {

        $this->mailer = $mailer;
        $this->myEmail = $myEmail;
        $this->noReplyEmail = $noReplyEmail;
    }
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {

        $this->sendWelcomeEmail($data);
        return $data;
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
