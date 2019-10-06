<?php

namespace BlackStork\Core\SonataAdmin;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use BlackStork\Core\Entity\Page as EntityPage;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PageSeoDataAdmin extends AbstractAdmin
{
    /**
     * @var string
     */
    protected $translationDomain = 'stork-core-backend';

    public function inject(ContainerInterface $container)
    {

    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var EntityPage $subject */
        $subject = $this->getSubject();

        $formMapper
            ->add('translations', GedmoTranslationsType::class, [
                'translatable_class' => 'BlackStork\Core\Entity\PageSeoData',
                'translation_domain' => $this->translationDomain,
                'label' => false,
                'fields' => [
                    'title' => ['required' => false],
                    'metaDescription' => [
                        'required' => false,
                        'attr' => ['rows' => 5]
                    ],
                    'metaKeywords' => [
                        'required' => false,
                        'attr' => ['rows' => 5]
                    ]
                ]
            ])
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