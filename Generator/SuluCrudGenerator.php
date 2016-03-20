<?php


namespace L91\Sulu\Bundle\BackendBundle\Generator;

use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class SuluCrudGenerator extends Generator
{
    /**
     * Constructor.
     *
     * @param Filesystem $filesystem A Filesystem instance
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem  = $filesystem;
    }

    /**
     * Generate the CRUD controller.
     *
     * @param BundleInterface   $bundle           A bundle object
     * @param string            $entity           The entity relative class name
     * @param ClassMetadataInfo $metadata         The entity class metadata
     * @param bool              $extended         Generate extended Controller
     * @param bool              $forceOverwrite   Force overwrite of exist
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function generateController(
        BundleInterface $bundle,
        $entity,
        ClassMetadataInfo $metadata,
        $extended = false,
        $forceOverwrite = false
    ) {
        $dir = $bundle->getPath();
        $parts = explode('\\', $entity);
        $entityClass = array_pop($parts);
        $entityNamespace = implode('\\', $parts);

        $target = sprintf(
            '%s/Controller/%s/%sController.php',
            $dir,
            str_replace('\\', '/', $entityNamespace),
            $entityClass
        );

        if (!$forceOverwrite && file_exists($target)) {
            throw new \RuntimeException('Unable to generate the controller as it already exists.');
        }

        $parameters = array(
            'bundle'            => $bundle->getName(),
            'bundle_prefix'     => self::getBundlePrefix($bundle),
            'entity_pluralize'  => Inflector::pluralize($entity),
            'entity'            => $entity,
            'metadata'          => $metadata,
            'entity_class'      => $entityClass,
            'namespace'         => $bundle->getNamespace(),
            'entity_namespace'  => $entityNamespace,
        );

        $template = 'sulu/controller/controller.php.twig';

        if ($extended) {
            $template = 'sulu/controller/controller_extended.php.twig';
        }

        $this->renderFile($template, $target, $parameters);

        return $this->render('sulu/controller/routing_api.yml.twig', $parameters);
    }

    /**
     * @param BundleInterface $bundle
     *
     * @return mixed
     */
    protected static function getBundlePrefix(BundleInterface $bundle)
    {
        return str_replace('_bundle', '', Inflector::tableize($bundle->getName()));
    }
}
