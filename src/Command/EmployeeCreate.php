<?php

namespace BlackStork\Core\Command;

use BlackStork\Core\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmployeeCreate extends Command
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $securityPasswordEncoder;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param $securityPasswordEncoder
     * @param $entityManager
     */
    public function inject(
        UserPasswordEncoderInterface $securityPasswordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->securityPasswordEncoder = $securityPasswordEncoder;
        $this->entityManager = $entityManager;
    }



    protected function configure()
    {
        $this
            ->setName('employee:create')
            ->setDescription('Create Employee')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'name'
            )
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'email'
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'password'
            )
            ->addArgument(
                'role',
                InputArgument::REQUIRED,
                'Role'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $role = $input->getArgument('role');
        $employee = new Employee();

        $employee->setFirstName($name);
        $employee->setLastName('');
        $employee->setEmail($email);
        $employee->setPassword($this->securityPasswordEncoder->encodePassword($employee, $password));
        $employee->setRoles([$role]);
        $employee->setCreated(new \DateTime());

        $this->entityManager->persist($employee);
        $this->entityManager->flush($employee);

        $output->writeln("Employee have been created with id " . $employee->getId());
    }
}