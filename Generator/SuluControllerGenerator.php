<?php

namespace L91\Sulu\Bundle\BackendBundle\Generator;

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

        return $this->render('sulu/controller/routing_api.' . $routeFormat . '.twig', $parameters);
    }
}
