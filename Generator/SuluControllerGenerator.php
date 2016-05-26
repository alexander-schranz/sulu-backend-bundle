<?php

namespace L91\Sulu\Bundle\BackendBundle\Generator;

use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class SuluControllerGenerator extends AbstractSuluGenerator
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

        // controller
        $controllerTarget = self::getTarget('%s/Controller/%s/%sController.php', $bundle, $entity);

        if (!$forceOverwrite && file_exists($controllerTarget)) {
            throw new \RuntimeException('Unable to generate the controller as it already exists.');
        }

        $this->renderFile('sulu/controller/controller.php.twig', $controllerTarget, $parameters);

        // template
        $templateTarget = sprintf(
            '%s/Resources/views/%s/template.html.twig',
            $bundle->getPath(),
            strtolower(Inflector::pluralize($entity))
        );

        if (!$forceOverwrite && file_exists($templateTarget)) {
            throw new \RuntimeException('Unable to generate the template as it already exists.');
        }

        $this->renderFile('sulu/controller/template.html.twig.twig', $templateTarget, $parameters);

        return $this->render('sulu/controller/routing_api.' . $routeFormat . '.twig', $parameters);
    }
}
