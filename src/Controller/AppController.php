<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Repository\CategoryRepository;

class AppController extends AbstractController
{
  
    function base(CategoryRepository $repoCat){
        $categories = $repoCat->findAll();
        return $this->render('base.html.twig', [
            'categories' => $categories
        ]);

    }

}
