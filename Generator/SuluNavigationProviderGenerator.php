<?php

namespace L91\Sulu\Bundle\BackendBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class SuluNavigationProviderGenerator extends AbstractSuluGenerator
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

        if ($extended) {
            $target = self::getTarget('%s/Admin/%s/%sNavigationProvider.php', $bundle, $entity);

            if (!$forceOverwrite && file_exists($target)) {
                throw new \RuntimeException('Unable to generate the navigation provider as it already exists.');
            }

            $this->renderFile('sulu/navigation-provider/navigation-provider.php.twig', $target, $parameters);
        }

        return $this->render('sulu/navigation-provider/services.' . $serviceFormat . '.twig', $parameters);
    }
}
