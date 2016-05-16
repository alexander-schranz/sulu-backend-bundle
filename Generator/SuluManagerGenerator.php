<?php

namespace L91\Sulu\Bundle\BackendBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class SuluManagerGenerator extends AbstractSuluGenerator
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
        $target = self::getTarget('%s/Manager/%s/%sManager.php', $bundle, $entity);

        if (!$forceOverwrite && file_exists($target)) {
            throw new \RuntimeException('Unable to generate the manager as it already exists.');
        }

        $parameters = self::getParameters($bundle, $entity, $metadata, $extended);

        $this->renderFile('sulu/manager/manager.php.twig', $target, $parameters);

        return $this->render('sulu/manager/services.yml.twig', $parameters);
    }
}
