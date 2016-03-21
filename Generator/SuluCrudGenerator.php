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
        $target = self::getTarget('%s/Controller/%s/%sController.php', $bundle, $entity);

        if (!$forceOverwrite && file_exists($target)) {
            throw new \RuntimeException('Unable to generate the controller as it already exists.');
        }

        $parameters = self::getParameters($bundle, $entity, $metadata, $extended);

        $this->renderFile('sulu/controller/controller.php.twig', $target, $parameters);

        return $this->render('sulu/controller/routing_api.yml.twig', $parameters);
    }

    /**
     * @param BundleInterface $bundle
     * @param $entity
     * @param ClassMetadataInfo $metadata
     * @param bool $extended
     * @param bool $forceOverwrite
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function generateManager(
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

    /**
     * @param $target
     * @param $bundle
     * @param $entity
     *
     * @return string
     */
    protected static function getTarget($target, BundleInterface $bundle, $entity)
    {
        $dir = $bundle->getPath();
        $parts = explode('\\', $entity);
        $entityClass = array_pop($parts);
        $entityNamespace = implode('\\', $parts);

        return  sprintf(
            $target,
            $dir,
            str_replace('\\', '/', $entityNamespace),
            $entityClass
        );
    }

    /**
     * @param BundleInterface $bundle
     * @param $entity
     * @param $metadata
     * @param $extended
     *
     * @return array
     */
    protected static function getParameters(
        BundleInterface $bundle,
        $entity,
        $metadata,
        $extended
    ) {
        $parts = explode('\\', $entity);
        $entityClass = array_pop($parts);
        $entityNamespace = implode('\\', $parts);

        return array(
            'bundle'            => $bundle->getName(),
            'bundle_prefix'     => self::getBundlePrefix($bundle),
            'entity_pluralize'  => Inflector::pluralize($entity),
            'entity'            => $entity,
            'metadata'          => $metadata,
            'entity_class'      => $entityClass,
            'extended'          => $extended,
            'namespace'         => $bundle->getNamespace(),
            'entity_namespace'  => $entityNamespace,
        );
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
