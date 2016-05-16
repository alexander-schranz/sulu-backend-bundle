<?php

namespace L91\Sulu\Bundle\BackendBundle\Command;

use L91\Sulu\Bundle\BackendBundle\Generator\AbstractSuluGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

abstract class AbstractGenerateCommand extends GenerateDoctrineCommand
{
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Force Overwrite
        $forceOverwrite = $input->getOption('force');

        // Generate Extended Classes
        $extended = $input->getOption('extended');

        // get entity data
        $entity = $input->getArgument('entity');

        $entity = Validators::validateEntityName($entity);
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle) . '\\' . $entity;
        $metadata = $this->getEntityMetadata($entityClass);

        $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);

        // Load Generator

        /** @var AbstractSuluGenerator $generator */
        $generator = $this->getGenerator($bundle);

        // Generate
        $registration = $generator->generate($bundle, $entity, $metadata[0], $extended, $forceOverwrite);

        $output->writeln(sprintf(
            '<info>%s</info>',
            $registration
        ));

        $output->writeln(PHP_EOL . '<info>Generator "' . $this->getName() . '" finished</info>');
    }

    /**
     * {@inheritdoc}
     */
    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $dirs = parent::getSkeletonDirs($bundle);
        $dirs[] = __DIR__ . '/../Resources/skeleton';

        return $dirs;
    }
}
