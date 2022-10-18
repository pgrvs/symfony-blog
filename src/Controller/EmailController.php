<?php

namespace App\Controller;

use App\Service\EmailService;
use PharIo\Manifest\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function index(EmailService $emailService): Response
    {
        $emailService->envoyerEmail('expediteur@test.fr', 'destinataire@test.fr', "Test envoie d'email",
            "email/email.html.twig",[
                'prenom'=>'Liam',
                'nom'=>'Genebrier'
            ]);
        return $this->redirectToRoute("app_articles");
    }
}
