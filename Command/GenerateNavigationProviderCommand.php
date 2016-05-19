<?php

namespace L91\Sulu\Bundle\BackendBundle\Command;

use L91\Sulu\Bundle\BackendBundle\Generator\SuluNavigationProviderGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateNavigationProviderCommand extends AbstractGenerateCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('l91:sulu:backend:generate:navigation-provider')
            ->setDescription( 'Will create a navigation provider for a specific entity.')
            ->addArgument('entity', InputArgument::REQUIRED, 'Enter your entity e.g.: AcmeBlogBundle:Post')
            ->addOption('extended', 'ex', InputOption::VALUE_NONE, 'Generate extended classes')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force Overwrite');
    }

    /**
     * @return SuluJSGenerator
     */
    protected function createGenerator()
    {
        return new SuluNavigationProviderGenerator($this->getContainer()->get('filesystem'));
    }
}
