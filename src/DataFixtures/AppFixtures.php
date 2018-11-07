<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Comment;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

       for ($i=1; $i <= 4 ; $i++) { 
            $category = new Category();
            $category->setTitle($faker->word())
                     ->setDescription($faker->paragraph());
          $manager->persist($category);
          for ($j=1; $j <= mt_rand(4,6) ; $j++) { 
            $activity = new Activity();
            $activity->setTitle($faker->sentence($nbWords = 4, $variableNbWords = true))
                    ->setDescription($faker->paragraph())
                    ->setNote($faker->numberBetween($min = 1, $max = 5))
                    ->setCategory($category);
                    $manager->persist($activity);
                    for ($k=1; $k <= mt_rand(4,10) ; $k++) { 
                        $comment = new Comment();
                        $comment->setAuthor($faker->name)
                                ->setSite($faker->url)
                                ->setMsg($faker->paragraph())
                                ->setNote($faker->numberBetween($min = 1, $max = 5))
                                ->setActivity($activity);
                                $manager->persist($comment);
                        }
                }
        }
        $manager->flush();
    }
}
