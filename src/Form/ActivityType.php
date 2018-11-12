<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('note')
            ->add('category', EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'title'
            ])
            ->add('pictures', CollectionType::class, array(
                'entry_type' => PictureType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
            ))
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
