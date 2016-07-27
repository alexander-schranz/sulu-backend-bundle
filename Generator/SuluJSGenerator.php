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
        $serviceFormat = 'yml',
        $routeFormat = 'yml',
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

        // list.js
        $listTarget = sprintf(
            '%s/Resources/public/js/components/%s/list/main.js',
            $bundle->getPath(),
            strtolower(Inflector::pluralize($entity))
        );

        if (!$forceOverwrite && file_exists($listTarget)) {
            throw new \RuntimeException('Unable to generate the list js as it already exists.');
        }

        $this->renderFile('sulu/js/list.js.twig', $listTarget, $parameters);

        // list.html
        $listTarget = sprintf(
            '%s/Resources/public/js/components/%s/list/list.html',
            $bundle->getPath(),
            strtolower(Inflector::pluralize($entity))
        );

        if (!$forceOverwrite && file_exists($listTarget)) {
            throw new \RuntimeException('Unable to generate the list html as it already exists.');
        }

        $this->renderFile('sulu/js/list.html.twig', $listTarget, $parameters);

        // edit.js
        $listTarget = sprintf(
            '%s/Resources/public/js/components/%s/edit/main.js',
            $bundle->getPath(),
            strtolower(Inflector::pluralize($entity))
        );

        if (!$forceOverwrite && file_exists($listTarget)) {
            throw new \RuntimeException('Unable to generate the edit js as it already exists.');
        }

        $this->renderFile('sulu/js/edit.js.twig', $listTarget, $parameters);

        // general.js
        $listTarget = sprintf(
            '%s/Resources/public/js/components/%s/edit/general/main.js',
            $bundle->getPath(),
            strtolower(Inflector::pluralize($entity))
        );

        if (!$forceOverwrite && file_exists($listTarget)) {
            throw new \RuntimeException('Unable to generate the general js as it already exists.');
        }

        $this->renderFile('sulu/js/general.js.twig', $listTarget, $parameters);

        // general.html
        $listTarget = sprintf(
            '%s/Resources/public/js/components/%s/edit/general/form.html',
            $bundle->getPath(),
            strtolower(Inflector::pluralize($entity))
        );

        if (!$forceOverwrite && file_exists($listTarget)) {
            throw new \RuntimeException('Unable to generate the general html as it already exists.');
        }

        $this->renderFile('sulu/js/general.html.twig', $listTarget, $parameters);

        // manager.js
        $listTarget = sprintf(
            '%s/Resources/public/js/services/%s-manager.js',
            $bundle->getPath(),
            strtolower($entity)
        );

        if (!$forceOverwrite && file_exists($listTarget)) {
            throw new \RuntimeException('Unable to generate the manager js as it already exists.');
        }

        $this->renderFile('sulu/js/manager.js.twig', $listTarget, $parameters);

        // router.js
        $listTarget = sprintf(
            '%s/Resources/public/js/services/%s-router.js',
            $bundle->getPath(),
            strtolower($entity)
        );

        if (!$forceOverwrite && file_exists($listTarget)) {
            throw new \RuntimeException('Unable to generate the router js as it already exists.');
        }

        $this->renderFile('sulu/js/router.js.twig', $listTarget, $parameters);

        // build
        exec(sprintf('cd %s && npm install && grunt build', $bundle->getPath()));
    }
}
