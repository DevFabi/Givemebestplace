<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;
use App\Entity\Activity;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\CommentRepository;
use App\Repository\ActivityRepository;

class CommentController extends AbstractController
{
    /**
     * @Route("/activity/comment/{id}", name="create_comment")
     */
    // Plus utilisé ( est dans activity controller)
    public function create(Activity $activity, Request $request, ObjectManager $manager)
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

        return $this->render('comment/create_comment.html.twig', [
            'formComment' => $form->createView(),
            'activity' => $activity
        ]);
    }
}
