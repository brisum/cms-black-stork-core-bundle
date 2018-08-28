<?php

namespace Brisum\Stork\Bundle\CoreBundle\Security;

use Brisum\Stork\Bundle\CoreBundle\Entity\Employee;
use Brisum\Stork\Bundle\CoreBundle\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProvider implements UserProviderInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * UserProvider constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $email
     * @return UserInterface
     *
     */
    public function loadUserByUsername($email)
    {
        /** @var EmployeeRepository $repository */
        $repository = $this->entityManager->getRepository(Employee::class);
        /** @var UserInterface $employee */
        $employee = $repository->findOneBy(['email' => $email]);

        if (!$employee) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $email));
        }

        return $employee;
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     * @return UserInterface
     *
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof UserInterface) {
            throw new UnsupportedUserException(sprintf(
                'Expected an instance of FOS\UserBundle\Model\UserInterface, but got "%s".',
                get_class($user)
            ));
        }

        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf(
                'Expected an instance of %s, but got "%s".',
                Employee::class, get_class($user)
            ));
        }

        /** @var EmployeeRepository $repository */
        $repository = $this->entityManager->getRepository(Employee::class);
        if (null === $reloadedUser = $repository->find($user->getId())) {
            throw new UsernameNotFoundException(sprintf('User with ID "%s" could not be reloaded.', $user->getId()));
        }

        return $reloadedUser;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return Employee::class === $class || is_subclass_of($class, Employee::class);
    }
}
