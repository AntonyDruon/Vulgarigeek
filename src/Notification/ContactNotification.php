<?php

namespace App\Notification;



use Twig\Environment;
use App\Entity\Contact;



class ContactNotification
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    // hors d'un controller, on peut faire des injections de dépendances seulement dans un constructeur

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact)
    {
        $message = (new \Swift_Message('Nouveau message de :'. $contact->getEmail())) // sujet du mail
                 ->setFrom($contact->getEmail())    // expéditeur du mail
                 ->setTo("vulgarigeek@gmail.com")       // destinataire du mail
                 ->setReplyTo($contact->getEmail()) // adresse de réponse du mail
                 ->setBody($this->renderer->render("emails/contact.html.twig", array(  // corps du message
                     'contact' => $contact
                 )), 'text/html');   // le corps du message sera contenu dans un template de format html
        $this->mailer->send($message);   // envoie du mail
    }
}