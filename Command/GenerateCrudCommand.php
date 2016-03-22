<?php

namespace L91\Sulu\Bundle\BackendBundle\Command;

use L91\Sulu\Bundle\BackendBundle\Generator\SuluCrudGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class GenerateCrudCommand extends GenerateDoctrineCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('l91:sulu:backend:generate:crud')
            ->setDescription('Will create crud process for a specific entity.')
            ->addArgument('entity', InputArgument::REQUIRED, 'Enter your entity e.g.: AcmeBlogBundle:Post')
            ->addOption('extended', 'ex', InputOption::VALUE_NONE, 'Generate extended classes')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force Overwrite');
    }

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

        /** @var SuluCrudGenerator $generator */
        $generator = $this->getGenerator($bundle);

        $questionHelper = $this->getQuestionHelper();

        // generate admin navigation
        $question = new ConfirmationQuestion(
            $questionHelper->getQuestion('Do you want to add a new Admin', 'yes', '?'),
            true
        );

        if (!$input->isInteractive() || $questionHelper->ask($input, $output, $question)) {
            $adminRegistration = $generator->generateAdmin($bundle, $entity, $metadata[0], $extended, $forceOverwrite);
            $output->writeln(PHP_EOL . 'Admin generated, register the Admin with: ' . PHP_EOL);
            $output->writeln(sprintf(
                '<info>%s</info>',
                $adminRegistration
            ));
        }

        // generate manager
        $question = new ConfirmationQuestion(
            $questionHelper->getQuestion('Do you want to generate a Manager', 'yes', '?'),
            true
        );

        if (!$input->isInteractive() || $questionHelper->ask($input, $output, $question)) {
            $managerRegistration = $generator->generateManager($bundle, $entity, $metadata[0], $extended, $forceOverwrite);
            $output->writeln(PHP_EOL . 'Manager generated, register the manager with: ' . PHP_EOL);
            $output->writeln(sprintf(
                '<info>%s</info>',
                $managerRegistration
            ));
        }

        // generate controller
        $question = new ConfirmationQuestion(
            $questionHelper->getQuestion('Do you want to generate a Controller', 'yes', '?'),
            true
        );

        if (!$input->isInteractive() || $questionHelper->ask($input, $output, $question)) {
            $controllerRegistration = $generator->generateController($bundle, $entity, $metadata[0], $extended, $forceOverwrite);
            $output->writeln(PHP_EOL . 'Controller generated, register the router with: ' . PHP_EOL);
            $output->writeln(sprintf(
                '<info>%s</info>',
                $controllerRegistration
            ));
        }

        // generate tab
        $question = new ConfirmationQuestion(
            $questionHelper->getQuestion('Do you want to add it as a new Tab to an exist entity', 'no', '?'),
            false
        );

        if ($input->isInteractive() && $questionHelper->ask($input, $output, $question)) {
            $output->writeln('Generate "Tab" not implemented yet');  // TODO generate tab
        }

        // generate js
        $question = new ConfirmationQuestion(
            $questionHelper->getQuestion('Do you want to generate the JS', 'yes', '?'),
            true
        );

        if (!$input->isInteractive() || $questionHelper->ask($input, $output, $question)) {
            $output->writeln('Generate "JS Bundle" not implemented yet');  // TODO generate js bundle
        }

        $output->writeln(PHP_EOL . '<info>Generator finished</info>');
    }

    /**
     * @return SuluCrudGenerator
     */
    protected function createGenerator()
    {
        return new SuluCrudGenerator($this->getContainer()->get('filesystem'));
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
