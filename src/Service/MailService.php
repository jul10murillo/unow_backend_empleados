<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendWelcomeEmail(string $toEmail, string $firstName): void
    {
        $htmlContent = $this->twig->render('emails/welcome.html.twig', [
            'firstName' => $firstName,
        ]);

        $email = (new Email())
            ->from('julioamurillo10@gmail.com')
            ->to($toEmail)
            ->subject('¡Bienvenido a la empresa!')
            ->html($htmlContent);

        $this->mailer->send($email);
    }
}