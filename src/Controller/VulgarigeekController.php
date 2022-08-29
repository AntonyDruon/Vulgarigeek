<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\Logiciel;
use App\Form\ContactType;
use App\Entity\Application;
use App\Entity\Commentairearticle;
use App\Entity\Commentairelogiciel;
use App\Form\CommentaireArticleType;
use App\Form\CommentairelogicielType;
use App\Repository\ArticleRepository;
use App\Entity\Commentaireapplication;
use App\Repository\LogicielRepository;
use App\Form\CommentaireApplicationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\ContactNotification;
use App\Repository\ApplicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VulgarigeekController extends AbstractController
{
    #[Route('/vulgarigeek', name: 'app_vulgarigeek')]
    public function index(ArticleRepository $repo): Response
    {
        return $this->render('vulgarigeek/index.html.twig', [
            
        ]);
    }
    // **********************Tout les articles**************************************
    #[Route('/vulgarigeek/articles', name: 'app_articles')]
    public function allArticle(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();


        return $this->render('vulgarigeek/listearticles.html.twig', [
            'articles' => $articles
        ]);
    }
    // **********************Tout les logiciels**************************************
    #[Route('/vulgarigeek/logiciels', name: 'app_logiciels')]
    public function allLogiciel(LogicielRepository $repo): Response
    {
        $logiciels = $repo->findAll();


        return $this->render('vulgarigeek/listelogiciels.html.twig', [
            'logiciels' => $logiciels
        ]);
    }

    // **********************Toute les applications**************************************
    #[Route('/vulgarigeek/applications', name: 'app_applications')]
    public function allApplication(ApplicationRepository $repo): Response
    {
        $applications = $repo->findAll();


        return $this->render('vulgarigeek/listeapplications.html.twig', [
            'applications' => $applications
        ]);
    }
    
    // ***************************formulaire de contact***********************************************
    #[Route('/vulgarigeek/contact', name:'app_contact')]
    public function contact(Request $request, EntityManagerInterface $manager, ContactNotification $notif)
     {
         $contact = new Contact;
         $form = $this->createForm(ContactType::class, $contact);
         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid())
         {
             $manager->persist($contact);
             $manager->flush();

             // On appelle la méthode de ContactNotification et on envoie en param les données du form
             $notif->notify($contact);
             // notify est la méthode que nous avons écrite pour envoyer les mails (Notification\ContactNotification.php)
             $this->addFlash('success', 'Votre message a bien été envoyé!');
             return $this->redirectToRoute('app_contact');
             // la redirection permet de recharger la page et vider les champs du formulaire
             //addFlash() est une méthode qui permet de stocker des messages de notification pour les afficher à l'utilisateur
         }
         return $this->render("vulgarigeek/contact.html.twig", array(
             'formContact' => $form->createView()
         ));
         
     }

     // ***********************Un article + commentaire**************************************

    #[Route('/vulgarigeek/article/{id}', name: 'app_article')]
    public function showArticle(Article $article,Request $request,EntityManagerInterface $manager): Response
    {   
        if(!$article){
            return $this->render('404.html.twig');
            // si $article = null, cela veut dire que j'essaye d'accéder à un article inexistant
            // j'affiche donc mon erreur 404
        }
        
        $commentairearticle = new Commentairearticle();
        $commentairearticle->setArticle($article)
                           ->setUtilisateur($this->getUser())
                           ->setdatecreation(new \DateTime());

        $form = $this->createForm(CommentaireArticleType::class, $commentairearticle);
        $form->handleRequest($request);
        dump($commentairearticle);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($commentairearticle);
            $manager->flush();

            return $this->redirectToRoute('app_article', array(
                'id' => $article->getId()
            ));
        }

        return $this->render("vulgarigeek/article.html.twig", array(
            'article' => $article,
            'formCommentairearticle' => $form->createView()
        ));
    }

      // ***********************Un logiciel + commentaire**************************************

      #[Route('/vulgarigeek/logiciel/{id}', name: 'app_logiciel')]
      public function showlogiciel(Logiciel $logiciel,Request $request,EntityManagerInterface $manager): Response
      {   
          if(!$logiciel){
              return $this->render('404.html.twig');
              // si $article = null, cela veut dire que j'essaye d'accéder à un article inexistant
              // j'affiche donc mon erreur 404
          }
          
          $commentairelogiciel = new Commentairelogiciel();
          $commentairelogiciel->setLogiciel($logiciel)
                             ->setUtilisateur($this->getUser())
                             ->setdatecreation(new \DateTime());
  
          $form = $this->createForm(CommentairelogicielType::class, $commentairelogiciel);
          $form->handleRequest($request);
          dump($commentairelogiciel);
          if($form->isSubmitted() && $form->isValid())
          {
              $manager->persist($commentairelogiciel);
              $manager->flush();
  
              return $this->redirectToRoute('app_logiciel', array(
                  'id' => $logiciel->getId()
              ));
          }
  
          return $this->render("vulgarigeek/logiciel.html.twig", array(
              'logiciel' => $logiciel,
              'formCommentairelogiciel' => $form->createView()
          ));
      }

       // ***********************Une application + commentaire**************************************

       #[Route('/vulgarigeek/application/{id}', name: 'app_application')]
       public function showApplication(Application $application,Request $request,EntityManagerInterface $manager): Response
       {   
           if(!$application){
               return $this->render('404.html.twig');
               // si $application= null, cela veut dire que j'essaye d'accéder à une application inexistante
               // j'affiche donc mon erreur 404
           }
           
           $commentaireapplication = new Commentaireapplication();
           $commentaireapplication->setApplication($application)
                                  ->setUtilisateur($this->getUser())
                                  ->setdatecreation(new \DateTime());
   
           $form = $this->createForm(CommentaireApplicationType::class, $commentaireapplication);
           $form->handleRequest($request);
           dump($commentaireapplication);
           if($form->isSubmitted() && $form->isValid())
           {
               $manager->persist($commentaireapplication);
               $manager->flush();
   
               return $this->redirectToRoute('app_application', array(
                   'id' => $application->getId()
               ));
           }
   
           return $this->render("vulgarigeek/application.html.twig", array(
               'application' => $application,
               'formCommentaireapplication' => $form->createView()
           ));
       }

}
