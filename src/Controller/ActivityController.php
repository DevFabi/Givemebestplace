<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ActivityRepository;
use App\Repository\CategoryRepository;
use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Picture;
use App\Form\CommentType;
use App\Form\ActivityType;
use App\Form\PictureType;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class ActivityController extends Controller
{
     /**
     * @Route("/", name="home")
     */
    public function home(ActivityRepository $repo, CategoryRepository $repoCat,Request $request)
    {
        $allActivities = $repo->findAll();
        $categories = $repoCat->findAll();

        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');
        
        // Paginate the results of the query
        $activities = $paginator->paginate(
            // Doctrine Query, not results
            $allActivities,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        return $this->render('home.html.twig', [
            'activities' => $activities,
            'categories' => $categories
        ]);
    }

      /**
     * @Route("/activity/{id}", name="show_activity")
     */
    public function show_activity(Activity $activity,Request $request, ObjectManager $manager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        
        // On récupère l'utilisateur qui a posté le commentaire
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
           $comment->setUser($user); 
           $comment->setAuthor("A effacer"); 
           $comment->setActivity($activity);
           $comment->setCreatedAt(new \DateTime());
           $comment->setDeleted(0);
           $manager->persist($comment);
           $manager->flush();

        return $this->redirectToRoute('show_activity', ['id' => $activity->getId()]);
        }
        $allComments = $activity->getComments();
         /* @var $paginator \Knp\Component\Pager\Paginator */
         $paginator  = $this->get('knp_paginator');
        
         // Paginate the results of the query
         $comments = $paginator->paginate(
             // Doctrine Query, not results
             $allComments,
             // Define the page parameter
             $request->query->getInt('page', 1),
             // Items per page
             5
         );
      
        return $this->render('activity/show.html.twig', [
            'formComment' => $form->createView(),
            'activity' => $activity,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/create_activity", name="create_activity")
     */
    public function create_activity(Request $request, ObjectManager $manager){

        $activity = new Activity();
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
  
            $pictures = $activity->getPictures(); // On récupère toutes les photos ajoutées
          
            if($pictures){ // S'il y a des photos
                foreach ($pictures as $picture ) { // Pour chaque photo
                   $file = $picture->getUrl(); // On récupère l'url uploadé

                   if (UPLOAD_ERR_OK !== $file->getError()) {
                    throw new UploadException($file->getErrorMessage());
                }

                   $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension(); // On génère un nom de fichier
                   // Move the file to the directory where brochures are stored
                        try {
                            $file->move( // On l'ajoute dans le répertoire
                                $this->getParameter('pictures_directory'),
                                $fileName
                            );
                        } catch (FileException $e) {
                            // ... handle exception if something happens during file upload
                        }
                        $picture->setUrl($fileName);
                }
            }

            
           $activity->setCreatedAt(new \DateTime());
           $activity->setDeleted(0);
           $manager->persist($activity);
           $manager->flush();

        return $this->redirectToRoute('show_activity', ['id' => $activity->getId()]);
        }

        return $this->render('activity/create_activity.html.twig', [
            'formActivity' => $form->createView(),
            'activity' => $activity
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}