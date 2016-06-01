<?php

namespace L91\Sulu\Bundle\BackendBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class GenerateCrudCommand extends ContainerAwareCommand
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
            ->addOption('service-format', 'sf', InputOption::VALUE_REQUIRED, 'Set the format of service registrations.')
            ->addOption('route-format', 'rf', InputOption::VALUE_REQUIRED, 'Set the format of route registrations.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force Overwrite');
    }

    /**
     * @return QuestionHelper|\Symfony\Component\Console\Helper\HelperInterface
     */
    protected function getQuestionHelper()
    {
        $question = $this->getHelperSet()->get('question');
        if (!$question || get_class($question) !== 'Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper') {
            $this->getHelperSet()->set($question = new QuestionHelper());
        }

        return $question;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();

        // generate controller
        $question = new ConfirmationQuestion(
            $questionHelper->getQuestion('Do you want to generate a "Controller"', 'yes', '?'),
            true
        );

        if (!$input->isInteractive() || $questionHelper->ask($input, $output, $question)) {
            $application = $this->getApplication()->find('l91:sulu:backend:generate:controller');
            $application->run($input, $output);
        }

        // generate js
        $question = new ConfirmationQuestion(
            $questionHelper->getQuestion('Do you want to generate the JS', 'yes', '?'),
            true
        );

        if (!$input->isInteractive() || $questionHelper->ask($input, $output, $question)) {
            $application = $this->getApplication()->find('l91:sulu:backend:generate:js');
            $application->run($input, $output);
        }

        // generate content navigation provider
        $question = new ConfirmationQuestion(
            $questionHelper->getQuestion('Do you want to generate a navigation provider', 'yes', '?'),
            true
        );

        if ($input->isInteractive() && $questionHelper->ask($input, $output, $question)) {
            $application = $this->getApplication()->find('l91:sulu:backend:generate:navigation-provider');
            $application->run($input, $output);
        }

        // generate admin navigation
        $question = new ConfirmationQuestion(
            $questionHelper->getQuestion('Do you want to add a new "Admin"', 'yes', '?'),
            true
        );

        if (!$input->isInteractive() || $questionHelper->ask($input, $output, $question)) {
            $application = $this->getApplication()->find('l91:sulu:backend:generate:admin');
            $application->run($input, $output);
        }

        // generate manager
        $question = new ConfirmationQuestion(
            $questionHelper->getQuestion('Do you want to generate a "Manager"', 'yes', '?'),
            true
        );

        if (!$input->isInteractive() || $questionHelper->ask($input, $output, $question)) {
            $application = $this->getApplication()->find('l91:sulu:backend:generate:manager');
            $application->run($input, $output);
        }

        $output->writeln(PHP_EOL . '<info>Generator finished</info>');
    }
}
