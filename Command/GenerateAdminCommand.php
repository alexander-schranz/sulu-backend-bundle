<?php

namespace L91\Sulu\Bundle\BackendBundle\Command;

use L91\Sulu\Bundle\BackendBundle\Generator\SuluAdminGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateAdminCommand extends AbstractGenerateCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('l91:sulu:backend:generate:admin')
            ->setDescription('Will create a admin for a specific entity.')
            ->addArgument('entity', InputArgument::REQUIRED, 'Enter your entity e.g.: AcmeBlogBundle:Post')
            ->addOption('extended', 'ex', InputOption::VALUE_NONE, 'Generate extended classes')
            ->addArgument('service-format', 'sf', InputArgument::REQUIRED, 'Set the format of service registrations.')
            ->addArgument('route-format', 'rf', InputArgument::REQUIRED, 'Set the format of route registrations.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force Overwrite');
    }

    /**
     * @return SuluAdminGenerator
     */
    protected function createGenerator()
    {
        return new SuluAdminGenerator($this->getContainer()->get('filesystem'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getRegistrationMessage()
    {
        return 'Admin generated, register the admin with: ';
    }
}
