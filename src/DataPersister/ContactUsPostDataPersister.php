<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\ContactUs;


final class ContactUsPostDataPersister implements ContextAwareDataPersisterInterface
{
    private $decorated;
    private $mailer;
    private $myEmail;


    public function __construct(ContextAwareDataPersisterInterface $decorated, MailerInterface $mailer, $myEmail)
    {
        $this->decorated = $decorated;
        $this->mailer = $mailer;
        $this->myEmail = $myEmail;
    }

    public function supports($data, array $context = []): bool
    {
        //return $data instanceof ContactUs;
        return $this->decorated->supports($data, $context);

    }

    public function persist($data, array $context = [])
    {

        $result = $this->decorated->persist($data, $context);

        if (
            $data instanceof ContactUs && (
                ($context['collection_operation_name'] ?? null) === 'post' ||
                ($context['graphql_operation_name'] ?? null) === 'create'
            )
        ) {
            $this->sendWelcomeEmail($data);
        }

        return $result;
        // call your persistence layer to save $data
        return $data;
    }

    public function remove($data, array $context = [])
    {
        // call your persistence layer to delete $data
    }

    public function sendWelcomeEmail(ContactUs $contactUs)
    {
        $email = (new TemplatedEmail())
            ->from($contactUs->getEmail())
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

