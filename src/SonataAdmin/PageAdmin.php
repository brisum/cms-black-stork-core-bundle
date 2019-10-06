<?php

namespace BlackStork\Core\SonataAdmin;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use BlackStork\Core\Entity\Page as EntityPage;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PageAdmin extends AbstractAdmin
{
    /**
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'id',
    );

    /**
     * @var string
     */
    protected $translationDomain = 'stork-core-backend';

    /**
     * @var array
     */
    protected $entityTemplates = [];

    /**
     * @var array
     */
    protected $entityStatuses = [];

    /**
     * @param ContainerInterface $container
     */
    public function inject(ContainerInterface $container)
    {
        foreach (array_keys($container->getParameter('black_stork_core.page.templates')) as $entityTemplate) {
            $this->entityTemplates[$entityTemplate] = $entityTemplate;
        }

        foreach ($container->getParameter('black_stork_core.page.statuses') as $statusValue =>  $statusTitle) {
            $this->entityStatuses[$statusTitle] = $statusValue;
        }
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('status')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('title')
            ->add('status')
            ->add('updated')
        ;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var EntityPage $subject */
        $subject = $this->getSubject();

        $isEditableName = empty($subject->getName()) || EntityPage::STATUS_DRAFT == $subject->getStatus();

        $formMapper
            ->with(false)
                ->add('name', null, ['attr' => ['readonly' => !$isEditableName]])
                ->add('status', ChoiceType::class, ['choices' => $this->entityStatuses])
                ->add('template', ChoiceType::class, ['choices' => $this->entityTemplates])
                ->add('translations', GedmoTranslationsType::class, [
                    'translatable_class' => 'BlackStork\Core\Entity\Page',
                    'translation_domain' => $this->translationDomain,
                    'label' => false,
                    'fields' => [
                        'title' => [],
                        'content' => [
                            'field_type' => TextareaType::class,
                            'attr' => ['class' => 'tinymce', 'rows' => 15]
                        ],
                    ]
                ])
            ->end()
            ->with('SeoData')
            ->add(
                'seoData',
                AdminType::class,
                [],
                ['edit' => 'inline']
            )
            ->end()
        ;

        if (!empty($subject->getCreated())) {
            $formMapper
                ->with('Time')
                ->add(
                    'created',
                    DateTimeType::class,
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
                    DateTimeType::class,
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
}
