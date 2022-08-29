<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Logiciel;
use App\Form\ArticleType;
use App\Form\LogicielType;
use App\Entity\Application;
use App\Entity\Utilisateur;
use App\Form\ApplicationType;
use App\Entity\Commentairearticle;
use App\Entity\Commentairelogiciel;
use App\Repository\ArticleRepository;
use App\Entity\Commentaireapplication;
use App\Repository\LogicielRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ApplicationRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentairearticleRepository;
use App\Repository\CommentairelogicielRepository;
use App\Repository\CommentaireapplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/article/new', name: 'app_admin_newarticle')]
    #[Route('/admin/{id}/edit-article', name: 'app_admin_editarticle')]

    public function formArticle(EntityManagerInterface $manager, Article $article = null, Request $request){
        if (!$article)
        {
            $article = new Article;
            $article->setDatedecreation(new \DateTime());
        }
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // dd($article);
            $manager->persist($article);
            $manager->flush();
            
            if ($request->attributes->get('_route')== 'app_admin_newarticle'){
                // si je me trovue sur la route d'ajout d'un article
                $this->addFlash("success", "Votre article '" . $article->getTitre() ."' a bien été crée !");
            }else{
                // sinon, je me trouve dans la route d'édition d'un article
                $this->addFlash("success", "Votre article '" . $article->getTitre() ."' a bien été modifié !");
            }
            
            return $this->redirectToRoute("admin_articles");     
      }
      return $this->render("admin/form_article.html.twig", [
          "formArticle" => $form->createView(),
          "editMode" => $article->getId() !== Null
      ]);
    }

    
        
    #[Route('/admin/article/delete/{id}', name: "delete_article")]
      public function deleteArticle(EntityManagerInterface $manager, Article $article = null){

        if (!$article){
        $this->addFlash('danger', 'Il n y a pas d\'article à supprimer');
        return $this->redirectToRoute('admin_articles');
        }

          $nom = $article->getTitre();
          $manager ->remove($article);
          // remove() est une méthode qui permet de préparer la suppression
          $manager->flush();

          $this->addFlash('success',"L'article '" . $nom . "' a bien été supprimé !");
          return $this->redirectToRoute('admin_articles');

          // la méthode deletearticle sert a supprimé a un article, pas besoin de template
      }


    
     
    
    #[Route('/admin/articles', name: 'admin_articles')]
     
    public function adminArticles(ArticleRepository $repo, EntityManagerInterface $manager){
        $colonnes = $manager->getClassMetadata(Article::class)->getFieldNames();
        // j'utilise le manager pour récupèrer le nom des champs de la table Article

       //  dd($colonnes);
        //dd() ça signifie dump $ die: afficher des données et tuer l'exécution du code
        $articles = $repo->findAll();
        return $this->render("admin/admin_articles.html.twig", [
            'articles' => $articles,
            'colonnes' => $colonnes
        ]);

    }

    // *****************COMMENTAIREARTICLE*****************//

    // Montre tout les commentaires article

    #[Route('/admin/commentairearticle', name: "admin_commentairearticle")]
    public function adminComment(EntityManagerInterface $manager, CommentairearticleRepository $repo){
           
        $colonnes = $manager->getClassMetadata(Commentairearticle::class)->getFieldNames();
        

        $commentairearticles = $repo->findAll();
       return $this->render("admin/admin_commentairearticle.html.twig", [
            'commentairearticle' => $commentairearticles,
            'colonnes' => $colonnes
       ]);
   }

   // delete commentairearticle

   #[Route('/admin/commentairearticle/delete/{id}', name: "delete_commentairearticle")]

   public function deleteComment(EntityManagerInterface $manager, Commentairearticle $commentairearticle = null){

    if (!$commentairearticle){
    $this->addFlash('danger', 'Il n y a pas de commentaire à supprimer');
    return $this->redirectToRoute('admin_articles');
    }

      $nom = $commentairearticle->getArticle()->getTitre();
      $manager ->remove($commentairearticle);
      // remove() est une méthode qui permet de préparer la suppression
      $manager->flush();

      $this->addFlash('success',"Le commentaire de l'article '" . $nom . "' a bien été supprimé !");
      return $this->redirectToRoute('admin_commentairearticle');

      // la méthode deletecommentairearticle sert a supprimé a un article, pas besoin de template
  }

  // ******************************UTILISATEUR***************************//

   // Montre tout les utilisateurs
   
  #[Route('/admin/utilisateur', name: "admin_utilisateur")]

    public function adminUser(EntityManagerInterface $manager, UtilisateurRepository $repo){
    $colonnes = $manager->getClassMetadata(Utilisateur::class)->getFieldNames();

    $utilisateur =$repo->findAll();
    return $this->render("admin/admin_utilisateur.html.twig", [
        'utilisateur' => $utilisateur,
        'colonnes'=>$colonnes
    ]);
}

    // Delete utilisateur

    #[Route('/admin/utilisateur/delete/{id}', name: "delete_utilisateur")]
    public function deleteUtilisateur(EntityManagerInterface $manager, Utilisateur $utilisateur = null){
        if (!$utilisateur){
            $this->addFlash('danger', 'Il n y a pas d\'utilisateur à supprimer');
            return $this->redirectToRoute('admin_utilisateur');
        }

        $nom = $utilisateur->getEmail();
        $manager->remove($utilisateur);
        $manager->flush();

        $this->addFlash('success', "L'utilisateur '" . $nom . "' a bien été supprimé!");
        return $this->redirectToRoute('admin_utilisateur');
    }


    // ***********************LOGICIEL**************************

    // nouveau logiciel ou edition

    #[Route('/admin/logiciel/new', name: 'app_admin_newlogiciel')]
    #[Route('/admin/{id}/edit-logiciel', name: 'app_admin_editlogiciel')]

    public function formLogiciel(EntityManagerInterface $manager, Logiciel $logiciel = null, Request $request){
        if (!$logiciel)
        {
            $logiciel = new Logiciel;
            
        }
        $form = $this->createForm(LogicielType::class, $logiciel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // dd($article);
            $manager->persist($logiciel);
            $manager->flush();
            
            if ($request->attributes->get('_route')== 'app_admin_newlogiciel'){
                // si je me trovue sur la route d'ajout d'un logiciel
                $this->addFlash("success", "Votre logiciel '" . $logiciel->getTitre() ."' a bien été crée !");
            }else{
                // sinon, je me trouve dans la route d'édition d'un logiciel
                $this->addFlash("success", "Votre logiciel '" . $logiciel->getTitre() ."' a bien été modifié !");
            }
            
            return $this->redirectToRoute("admin_logiciels");     
      }
      return $this->render("admin/form_logiciel.html.twig", [
          "formLogiciel" => $form->createView(),
          "editMode" => $logiciel->getId() !== Null
      ]);
    }


    // voir tout les logiciels

    #[Route('/admin/logiciels', name: 'admin_logiciels')]
     
    public function adminLogiciel(LogicielRepository $repo, EntityManagerInterface $manager){
        $colonnes = $manager->getClassMetadata(Logiciel::class)->getFieldNames();
        // j'utilise le manager pour récupèrer le nom des champs de la table Article

       //  dd($colonnes);
        //dd() ça signifie dump $ die: afficher des données et tuer l'exécution du code
        $logiciels = $repo->findAll();
        return $this->render("admin/admin_logiciels.html.twig", [
            'logiciels' => $logiciels,
            'colonnes' => $colonnes
        ]);

    }

    // supprimer un logiciel

    #[Route('/admin/logiciel/delete/{id}', name: "delete_logiciel")]
      public function deletelogiciel(EntityManagerInterface $manager, logiciel $logiciel = null){

        if (!$logiciel){
        $this->addFlash('danger', 'Il n y a pas de logiciel à supprimer');
        return $this->redirectToRoute('admin_logiciels');
        }

          $nom = $logiciel->getTitre();
          $manager ->remove($logiciel);
          // remove() est une méthode qui permet de préparer la suppression
          $manager->flush();

          $this->addFlash('success',"le logiciel '" . $nom . "' a bien été supprimé !");
          return $this->redirectToRoute('admin_logiciels');

          // la méthode deletearticle sert a supprimé a un article, pas besoin de template
      }

       // *****************COMMENTAIRELOGICIEL*****************//

    // Montre tout les commentaires logiciel

    #[Route('/admin/commentairelogiciel', name: "admin_commentairelogiciel")]
    public function adminCommentaireLogiciel(EntityManagerInterface $manager, CommentairelogicielRepository $repo){
           
        $colonnes = $manager->getClassMetadata(Commentairelogiciel::class)->getFieldNames();
        

        $commentairelogiciels = $repo->findAll();
       return $this->render("admin/admin_commentairelogiciel.html.twig", [
            'commentairelogiciel' => $commentairelogiciels,
            'colonnes' => $colonnes
       ]);
   }

   // delete commentairelogiciel

   #[Route('/admin/commentairelogiciel/delete/{id}', name: "delete_commentairelogiciel")]

   public function deleteCommentaireLogiciel(EntityManagerInterface $manager, Commentairelogiciel $commentairelogiciel = null){

    if (!$commentairelogiciel){
    $this->addFlash('danger', 'Il n y a pas de commentaire à supprimer');
    return $this->redirectToRoute('admin_logiciels');
    }

      $nom = $commentairelogiciel->getLogiciel()->getTitre();
      $manager ->remove($commentairelogiciel);
      // remove() est une méthode qui permet de préparer la suppression
      $manager->flush();

      $this->addFlash('success',"Le commentaire du logiciel '" . $nom . "' a bien été supprimé !");
      return $this->redirectToRoute('admin_commentairelogiciel');

      // la méthode deletecommentairelogiciel sert a supprimé un logiciel, pas besoin de template
  }

  // ***********************APPLICATION**************************

    // nouvelle application ou edition

    #[Route('/admin/application/new', name: 'app_admin_newapplication')]
    #[Route('/admin/{id}/edit-application', name: 'app_admin_editapplication')]

    public function formApplication(EntityManagerInterface $manager, Application $application = null, Request $request){
        if (!$application)
        {
            $application = new Application;
            
        }
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // dd($article);
            $manager->persist($application);
            $manager->flush();
            
            if ($request->attributes->get('_route')== 'app_admin_newapplication'){
                // si je me trovue sur la route d'ajout d'un application
                $this->addFlash("success", "Votre application '" . $application->getTitre() ."' a bien été crée !");
            }else{
                // sinon, je me trouve dans la route d'édition d'un application
                $this->addFlash("success", "Votre application '" . $application->getTitre() ."' a bien été modifié !");
            }
            
            return $this->redirectToRoute("admin_applications");     
      }
      return $this->render("admin/form_application.html.twig", [
          "formApplication" => $form->createView(),
          "editMode" => $application->getId() !== Null
      ]);
    }

     // voir toute les applications

     #[Route('/admin/applications', name: 'admin_applications')]
     
     public function adminApplication(ApplicationRepository $repo, EntityManagerInterface $manager){
         $colonnes = $manager->getClassMetadata(Application::class)->getFieldNames();
         // j'utilise le manager pour récupèrer le nom des champs de la table Application
 
        //  dd($colonnes);
         //dd() ça signifie dump $ die: afficher des données et tuer l'exécution du code
         $applications = $repo->findAll();
         return $this->render("admin/admin_applications.html.twig", [
             'applications' => $applications,
             'colonnes' => $colonnes
         ]);
 
     }
 
     // supprimer une application
 
     #[Route('/admin/application/delete/{id}', name: "delete_application")]
       public function deleteapplication(EntityManagerInterface $manager, application $application = null){
 
         if (!$application){
         $this->addFlash('danger', 'Il n y a pas d\'application à supprimer');
         return $this->redirectToRoute('admin_applications');
         }
 
           $nom = $application->getTitre();
           $manager ->remove($application);
           // remove() est une méthode qui permet de préparer la suppression
           $manager->flush();
 
           $this->addFlash('success',"l\'application '" . $nom . "' a bien été supprimé !");
           return $this->redirectToRoute('admin_applications');
 
           // la méthode deleteapplication sert a supprimé a une application, pas besoin de template
       }

        // *****************COMMENTAIREAPPLICATION*****************//

    // Montre tout les commentaires application

    #[Route('/admin/commentaireapplication', name: "admin_commentaireapplication")]
    public function adminCommentaireapplication(EntityManagerInterface $manager, CommentaireapplicationRepository $repo){
           
        $colonnes = $manager->getClassMetadata(Commentaireapplication::class)->getFieldNames();
        

        $commentaireapplications = $repo->findAll();
       return $this->render("admin/admin_commentaireapplication.html.twig", [
            'commentaireapplication' => $commentaireapplications,
            'colonnes' => $colonnes
       ]);
   }

   // delete commentaireapplication

   #[Route('/admin/commentaireapplication/delete/{id}', name: "delete_commentaireapplication")]

   public function deleteCommentairAapplication(EntityManagerInterface $manager, Commentaireapplication $commentaireapplication = null){

    if (!$commentaireapplication){
    $this->addFlash('danger', 'Il n y a pas de commentaire à supprimer');
    return $this->redirectToRoute('admin_application');
    }

      $nom = $commentaireapplication->getApplication()->getTitre();
      $manager ->remove($commentaireapplication);
      // remove() est une méthode qui permet de préparer la suppression
      $manager->flush();

      $this->addFlash('success',"Le commentaire de l'application '" . $nom . "' a bien été supprimé !");
      return $this->redirectToRoute('admin_commentaireapplication');

      // la méthode deletecommentaireapplication sert a supprimé une application, pas besoin de template
  }

}
