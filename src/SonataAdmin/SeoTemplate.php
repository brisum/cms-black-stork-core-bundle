<?php

namespace Brisum\Stork\Bundle\CoreBundle\SonataAdmin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Brisum\Stork\Bundle\CoreBundle\Entity\SeoTemplate as EntitySeoTemplate;

class SeoTemplate extends AbstractAdmin
{
    /**
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'template',
    );

    /**
     * @var array
     */
    protected $seoTemplates = [];

    public function setContainer(ContainerInterface $container)
    {
        foreach (array_keys($container->getParameter('seo.templates')) as $seoTemplate) {
            $this->seoTemplates[$seoTemplate] = $seoTemplate;
        }
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var EntitySeoTemplate $subject */
        $subject = $this->getSubject();

        $formMapper
            ->with('General')
                ->add('template', 'choice', ['choices' => $this->seoTemplates])
                ->add('title')
                ->add('meta_description', 'textarea', ['required' => false, 'attr' => ['rows' => 7]])
                ->add('meta_keywords', 'textarea', ['required' => false, 'attr' => ['rows' => 7]])
                ->add('breadcrumbs')
                ->add('h1')
                ->add('content', 'textarea', ['required' => false, 'attr' => ['rows' => 7]])
            ->end()
        ;

        if (!empty($subject->getCreated())) {
            $formMapper
                ->with('Time')
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
                ->end()
            ;
        }
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('template')
            ->add('title')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('template')
            ->add('updated')
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