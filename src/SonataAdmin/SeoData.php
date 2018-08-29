<?php

namespace Brisum\Stork\Bundle\CoreBundle\SonataAdmin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class SeoData extends AbstractAdmin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('url')
            ->add('title')
            ->add('meta_description', 'textarea', ['attr' => ['rows' => 7]])
            ->add('meta_keywords', 'textarea', ['attr' => ['rows' => 7]])
            ->add('breadcrumbs')
            ->add('h1')
            ->add('content', 'textarea', ['attr' => ['rows' => 7]])
            ->add(
                'created',
                'datetime',
                [
                    'attr' => [
                        'class' => 'normal',
                        'readonly' => true
                    ],
                    "format" => "yyyy-dd-MM HH:mm:ss",
                    "widget" => "single_text"
                ]
            )
            ->add(
                'updated',
                'datetime',
                [
                    'attr' => [
                        'class' => 'normal',
                        'readonly' => true
                    ],
                    "format" => "yyyy-dd-MM HH:mm:ss",
                    "widget" => "single_text"
                ]
            )
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('url')
            ->add('title')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('url')
            ->addIdentifier('title')
            ->addIdentifier('created')
            ->addIdentifier('updated')
        ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
//        $showMapper
//            ->add('title')
//            ->add('slug')
//            ->add('author')
//        ;
    }
}