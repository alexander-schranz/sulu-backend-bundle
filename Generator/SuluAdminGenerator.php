<?php

namespace L91\Sulu\Bundle\BackendBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class SuluAdminGenerator extends AbstractSuluGenerator
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

        if ($extended) {
            $target = self::getTarget('%s/Admin/%s/%sAdmin.php', $bundle, $entity);

            if (!$forceOverwrite && file_exists($target)) {
                throw new \RuntimeException('Unable to generate the admin as it already exists.');
            }

            $this->renderFile('sulu/admin/admin.php.twig', $target, $parameters);
        }

        return $this->render('sulu/admin/services.yml.twig', $parameters);
    }
}
