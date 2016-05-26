<?php

namespace L91\Sulu\Bundle\BackendBundle\Generator;

use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use L91\Sulu\Bundle\BackendBundle\Twig\ConverterTwigExtension;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

abstract class AbstractSuluGenerator extends Generator
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * SuluCrudGenerator constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(
        Filesystem $filesystem
    ) {
        $this->filesystem  = $filesystem;
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwigEnvironment()
    {
        $twigEnvironment = parent::getTwigEnvironment();

        $twigEnvironment->addExtension(new ConverterTwigExtension());

        return $twigEnvironment;
    }

    /**
     * @param BundleInterface $bundle
     * @param $entity
     * @param ClassMetadataInfo $metadata
     * @param string $serviceFormat
     * @param string $routeFormat
     * @param bool $extended
     * @param bool $forceOverwrite
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    abstract public function generate(
        BundleInterface $bundle,
        $entity,
        ClassMetadataInfo $metadata,
        $serviceFormat = 'yml',
        $routeFormat = 'yml',
        $extended = false,
        $forceOverwrite = false
    );

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

        return sprintf(
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
            'bundle' => $bundle->getName(),
            'bundle_prefix' => self::getBundlePrefix($bundle),
            'bundle_namespace' => self::getBundleNamespace($bundle),
            'entity_pluralize' => Inflector::pluralize($entity),
            'js_bundle_name' => self::getJSBundleName($bundle),
            'entity' => $entity,
            'metadata' => $metadata,
            'entity_class' => $entityClass,
            'extended' => $extended,
            'namespace' => $bundle->getNamespace(),
            'entity_namespace' => $entityNamespace,
            'public_translations' => self::getPublicTranslations(),
        );
    }

    /**
     * @param BundleInterface $bundle
     *
     * @return string
     */
    protected static function getBundlePrefix(BundleInterface $bundle)
    {
        return str_replace('_bundle', '', Inflector::tableize($bundle->getName()));
    }

    /**
     * @param BundleInterface $bundle
     *
     * @return string
     */
    protected static function getBundleNamespace(BundleInterface $bundle)
    {
        return explode('_', self::getBundlePrefix($bundle))[0];
    }

    /**
     * @param BundleInterface $bundle
     *
     * @return string
     */
    protected static function getJSBundleName(BundleInterface $bundle)
    {
        return str_replace('_', '', self::getBundlePrefix($bundle));
    }

    /**
     * @return array
     */
    protected static function getPublicTranslations()
    {
        return [
            'id', 'title', 'description', 'name', 'key', 'email', 'phone', 'changed', 'created', 'published', 'fax',
            'note', 'all', 'disabled', 'public', 'visible', 'number', 'status'
        ];
    }
}
