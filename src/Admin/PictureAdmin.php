<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PictureAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('url', TextType::class)
                   ->add('legende')
                   ->add('createdAt', HiddenType::class, array(
                    'data' => New \DateTime()
                ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('url')
                       ->add('legende');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('url');
    }
}