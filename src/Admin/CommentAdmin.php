<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Activity;
use App\Application\Sonata\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\CommentType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CommentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('author')
                    ->add('site')
                    ->add('msg')
                    ->add('note')
                   ->add('createdAt', DateType::class, array(
                    'widget' => 'choice',
                    'data' => New \DateTime()))
                   ->add('deleted', HiddenType::class, array(
                    'data' => 0 ))
                   ->add('activity', EntityType::class, [
                    'class' => Activity::class,
                    'choice_label' => 'title'])
                    ->add('user', EntityType::class, [
                        'class' => User::class,
                        'choice_label' => 'username']);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('user.username')
                       ->add('msg')
                       ->add('note');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('user.username')
                         ->add('msg')
                         ->add('createdAt')
                         ->add('note');
    }

    public function toString($object)
    {
        return $object instanceof Comment
            ? $object->getTitle()
            : 'Comment'; // shown in the breadcrumb on the create view
    }

}