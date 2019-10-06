<?php

namespace BlackStork\Core\SonataAdmin;

use BlackStork\Core\Entity\Employee;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmployeeAdmin extends AbstractAdmin
{
    /**
     * @var string
     */
    protected $translationDomain = 'stork-core-backend';

    /**
     * @var string
     */
    protected $baseRoutePattern = 'employee';

    /*
     * @var integer
     */
    protected $maxPerPage = 30;

    /**
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1,
        '_per_page' => 30,
        '_sort_order' => 'ASC',
        '_sort_by' => 'id',
    );

    /**
     * @var array
     */
    protected $perPageOptions = [30, 50, 100];

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            // ->remove('list')
            // ->remove('create')
            ->remove('batch')
            // ->remove('edit')
            // ->remove('delete')
            ->remove('show')
            ->remove('export')
        ;
    }

    /**
     * @param array $actions
     * @return array
     */
    public function configureBatchActions($actions)
    {
        if (isset($actions['delete'])) {
            unset($actions['delete']);
        }

        return $actions;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('firstName')
            ->add('lastName')
            ->add('email')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('firstName')
            ->addIdentifier('lastName')
            ->add('email')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->with('Employee')
            ->add('firstName')
            ->add('lastName')
            ->add('email')
             ->add('plainPassword', TextType::class, ['required' => false, 'mapped' => false])
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' => [
                        'ROLE_ADMIN' => 'ROLE_ADMIN'
                    ],
                    'multiple' => true
                ]
            )
        ->end();
    }

    /**
     * @param Employee $entity
     */
    public function prePersist($entity)
    {
        $this->preUpdate($entity);
    }

    /**
     * @param Employee $entity
     */
    public function preUpdate($entity)
    {
        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $this->getConfigurationPool()->getContainer()->get('security.password_encoder');
        $request = $this->getRequest();
        $uniqid = $request->query->get('uniqid');
        $plainPassword = $this->getRequest()->request->get($uniqid)['plainPassword'];

        if ($plainPassword) {
            $entity->setPassword($userPasswordEncoder->encodePassword($entity, $plainPassword));
        }
    }
}
