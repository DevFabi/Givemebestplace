<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryController extends AbstractController
{
      /**
     * @Route("/category/{id}", name="show_category")
     */
    public function show_category(Category $category)
    {
        $activities = $category->getActivities();
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'activities' => $activities
        ]);
    }
}
