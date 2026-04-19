<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email; 
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
public function index(RequestStack $requestStack): Response
{
    $session = $requestStack->getSession();
    
    // Lire le nombre de visites (0 si première fois)
    $nbVisites = $session->get('nb_visites', 0);
    
    // Incrémenter
    $nbVisites++;
    
    // Sauvegarder
    $session->set('nb_visites', $nbVisites);
    
    return $this->render('accueil/index.html.twig', [
        'controller_name' => 'AccueilController',
        'nb_visites' => $nbVisites
    ]);
}



    #[Route('/bonjour/{prenom}', name: 'app_bonjour')]
    public function bonjour(string $prenom): Response
    {
        return new Response("<h1>Bonjour $prenom ! Bienvenue sur Symfony 7.4</h1>");
    }

    #[Route('/profil/{id}', name: 'app_profil', requirements: ['id' => '\d+'], defaults: ['id' => 1])]
    public function profil(int $id): Response
    {
        return new Response("<h1>Profil de l'utilisateur n°$id</h1>");
    }














    #[Route('/test-email-twig', name: 'app_test_email_twig')]
public function testEmailTwig(MailerInterface $mailer): Response
{
    $email = (new TemplatedEmail())
        ->from('noreply@monsite.com')
        ->to('etudiant@exemple.com')
        ->subject('Nouvel article publié !')
        ->htmlTemplate('emails/notification.html.twig')
        ->context([
            'subject' => 'Nouvel article publié !',
            'message' => 'Un nouveau contenu vient d\'être ajouté sur le blog.',
        ]);

    $mailer->send($email);

    $this->addFlash('success', 'Email Twig envoyé ! Vérifiez Mailtrap.');

    return $this->redirectToRoute('app_accueil');
}



}
