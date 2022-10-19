<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private ContactRepository $contactRepository;

    /**
     * @param ContactRepository $contactRepository
     */
    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EmailService $emailService): Response
    {
       $contact = new Contact();
       $formContact = $this->createForm(ContactType::class, $contact);

       // Traitement du formulair
        $formContact->handleRequest($request);
        // Est-ce que le formulaire a été soumis
        if ($formContact->isSubmitted() && $formContact->isValid()){
            $contact->setCreatedAt(new \DateTime());
            // Insérer le contact dans la base de données
            $this->contactRepository->add($contact, true);
            $emailService->envoyerEmail(
                $contact->getEmail(),
                'admin@blog.fr',
                $contact->getObjet(),
                'email/emailContact.html.twig',
                [
                    'contenu' => $contact->getContenu(),
                    'nom' => $contact->getNom(),
                    'prenom' => $contact->getPrenom(),
                ]
            );
            return $this->redirectToRoute("app_articles");
        }

        return $this->renderForm('contact/index.html.twig', [
            'formContact' => $formContact,
        ]);
    }
}
