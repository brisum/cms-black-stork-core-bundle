<?php

namespace Brisum\Stork\Bundle\CoreBundle\SonataAdmin;

use Brisum\Stork\Bundle\CoreBundle\Entity\Page as EntityPage;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PageSeoData extends AbstractAdmin
{
    public function inject(ContainerInterface $container)
    {

    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var EntityPage $subject */
        $subject = $this->getSubject();

        $formMapper
            ->add('title', null, ['required' => false])
            ->add('metaDescription', null, ['required' => false, 'attr' => ['rows' => 5]])
            ->add('metaKeywords', null, ['required' => false, 'attr' => ['rows' => 5]])
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
    }
}