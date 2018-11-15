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

class ActivityAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class)
                   ->add('description')
                   ->add('note')
                   ->add('category', EntityType::class, [
                    'class' => Category::class,
                    'choice_label' => 'title',
                ])
                ->add('pictures', CollectionType::class, array(
                    'entry_type' => PictureType::class,
                    'entry_options' => array('label' => false),
                    'allow_add' => true,
                    'by_reference' => false,
                ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title')
                       ->add('description')
                       ->add('note');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title');
    }
}