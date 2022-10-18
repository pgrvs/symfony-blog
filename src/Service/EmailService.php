<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private MailerInterface $mailer;

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $expediteur
     * @param string $destinataire
     * @param string $objet
     * @param string $template
     * @param array $context
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function envoyerEmail(string $expediteur, string $destinataire, string $objet, string $template, array $context) : void{
        // Créer le mail
        $email = new TemplatedEmail();
        $email  ->from($expediteur)
            ->to($destinataire)
            ->subject($objet)
            ->htmlTemplate($template)
            ->context($context);
        // Envoyer le mail
        $this->mailer->send($email);
    }
}