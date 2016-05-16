<?php

namespace L91\Sulu\Bundle\BackendBundle\Generator;

use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class SuluJSGenerator extends AbstractSuluGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate(
        BundleInterface $bundle,
        $entity,
        ClassMetadataInfo $metadata,
        $extended = false,
        $forceOverwrite = false
    ) {
        $parameters = self::getParameters($bundle, $entity, $metadata, $extended);

        // package.json
        $packageTarget = sprintf('%s/package.json', $bundle->getPath());

        if (!$forceOverwrite && file_exists($packageTarget)) {
            throw new \RuntimeException('Unable to generate the package.json as it already exists.');
        }

        $this->renderFile('sulu/js/package.json.twig', $packageTarget, $parameters);

        // Gruntfile.js
        $gruntTarget = sprintf('%s/Gruntfile.js', $bundle->getPath());

        if (!$forceOverwrite && file_exists($gruntTarget)) {
            throw new \RuntimeException('Unable to generate the Gruntfile.js as it already exists.');
        }

        $this->renderFile('sulu/js/Gruntfile.js.twig', $gruntTarget, $parameters);

        // main.js
        $mainTarget = sprintf('%s/Resources/public/js/main.js', $bundle->getPath());

        if (!$forceOverwrite && file_exists($mainTarget)) {
            throw new \RuntimeException('Unable to generate the main.js as it already exists.');
        }

        $this->renderFile('sulu/js/main.js.twig', $mainTarget, $parameters);

        // component
        $componentTarget = sprintf(
            '%s/Resources/public/js/components/%s/main.js',
            $bundle->getPath(),
            strtolower(Inflector::pluralize($entity))
        );

        if (!$forceOverwrite && file_exists($componentTarget)) {
            throw new \RuntimeException('Unable to generate the component js as it already exists.');
        }

        $this->renderFile('sulu/js/component.js.twig', $componentTarget, $parameters);

        // list
        $listTarget = sprintf(
            '%s/Resources/public/js/components/%s/list/main.js',
            $bundle->getPath(),
            strtolower(Inflector::pluralize($entity))
        );

        if (!$forceOverwrite && file_exists($listTarget)) {
            throw new \RuntimeException('Unable to generate the list js as it already exists.');
        }

        $this->renderFile('sulu/js/list.js.twig', $listTarget, $parameters);

        // tabs
        $tabsTarget = sprintf(
            '%s/Resources/public/js/components/%s/tabs/main.js',
            $bundle->getPath(),
            strtolower(Inflector::pluralize($entity))
        );

        if (!$forceOverwrite && file_exists($tabsTarget)) {
            throw new \RuntimeException('Unable to generate the tabs js as it already exists.');
        }

        $this->renderFile('sulu/js/tabs.js.twig', $tabsTarget, $parameters);

        // form
        $formTarget = sprintf(
            '%s/Resources/public/js/components/%s/form/main.js',
            $bundle->getPath(),
            strtolower(Inflector::pluralize($entity))
        );

        if (!$forceOverwrite && file_exists($formTarget)) {
            throw new \RuntimeException('Unable to generate the form js as it already exists.');
        }

        $this->renderFile('sulu/js/form.js.twig', $formTarget, $parameters);

        // build
        exec(sprintf('cd %s && npm install && grunt build', $bundle->getPath()));
    }
}
