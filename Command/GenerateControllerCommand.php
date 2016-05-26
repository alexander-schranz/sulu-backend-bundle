<?php

namespace L91\Sulu\Bundle\BackendBundle\Command;

use L91\Sulu\Bundle\BackendBundle\Generator\SuluControllerGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateControllerCommand extends AbstractGenerateCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('l91:sulu:backend:generate:controller')
            ->setDescription('Will create a controller for a specific entity.')
            ->addArgument('entity', InputArgument::REQUIRED, 'Enter your entity e.g.: AcmeBlogBundle:Post')
            ->addOption('extended', 'ex', InputOption::VALUE_NONE, 'Generate extended classes')
            ->addArgument('service-format', 'sf', InputArgument::REQUIRED, 'Set the format of service registrations.')
            ->addArgument('route-format', 'rf', InputArgument::REQUIRED, 'Set the format of route registrations.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force Overwrite');
    }

    /**
     * @return SuluControllerGenerator
     */
    protected function createGenerator()
    {
        return new SuluControllerGenerator($this->getContainer()->get('filesystem'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getRegistrationMessage()
    {
        return 'Controller generated, register the route with: ';
    }
}
