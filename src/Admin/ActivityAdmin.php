<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Category;
use App\Entity\Picture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\PictureType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ActivityAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class)
                   ->add('description')
                   ->add('note')
                   ->add('createdAt', DateType::class, array(
                    'widget' => 'choice',
                    'data' => New \DateTime()))
                   ->add('deleted', HiddenType::class, array(
                    'data' => 0 ))
                   ->add('category', EntityType::class, [
                    'class' => Category::class,
                    'choice_label' => 'title'])
                   ->add('pictures', CollectionType::class, [
                    'entry_type' => PictureType::class,
                    'by_reference' => false, 
                    'allow_add' => true,
                    'allow_delete' => true, 
                    'prototype' => true]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title')
                       ->add('category.title')
                       ->add('description')
                       ->add('note');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title')
                     ->add('category.title');
    }

    public function toString($object)
    {
        return $object instanceof Activity
            ? $object->getTitle()
            : 'Activity'; // shown in the breadcrumb on the create view
    }

}