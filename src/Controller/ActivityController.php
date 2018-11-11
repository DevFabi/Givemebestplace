<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ActivityRepository;
use App\Repository\CategoryRepository;
use App\Entity\Activity;
use App\Entity\Category;
use App\Form\CommentType;
use App\Form\ActivityType;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\CommentRepository;
class ActivityController extends AbstractController
{
     /**
     * @Route("/", name="home")
     */
    public function home(ActivityRepository $repo, CategoryRepository $repoCat)
    {
        $activities = $repo->findAll();
        $categories = $repoCat->findAll();
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

        if ($form->isSubmitted() && $form->isValid()) {
           $comment->setActivity($activity);
           $comment->setCreatedAt(new \DateTime());
           $comment->setDeleted(0);
           $manager->persist($comment);
           $manager->flush();

        return $this->redirectToRoute('show_activity', ['id' => $activity->getId()]);
        }

        return $this->render('activity/show.html.twig', [
            'formComment' => $form->createView(),
            'activity' => $activity
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
}