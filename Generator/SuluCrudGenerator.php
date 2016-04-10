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
    public function generateAdmin(
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
    public function generateNavigationProvider(
        BundleInterface $bundle,
        $entity,
        ClassMetadataInfo $metadata,
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

        return $this->render('sulu/navigation-provider/services.yml.twig', $parameters);
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
    public function generateJS(
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

        // collection
        $collectionTarget = sprintf(
            '%s/Resources/public/js/collections/%s.js',
            $bundle->getPath(),
            strtolower(Inflector::pluralize($entity))
        );

        if (!$forceOverwrite && file_exists($collectionTarget)) {
            throw new \RuntimeException('Unable to generate the collection js as it already exists.');
        }

        $this->renderFile('sulu/js/collection.js.twig', $collectionTarget, $parameters);

        // collection
        $modelTarget = sprintf(
            '%s/Resources/public/js/model/%s.js',
            $bundle->getPath(),
            strtolower($entity)
        );

        if (!$forceOverwrite && file_exists($modelTarget)) {
            throw new \RuntimeException('Unable to generate the model js as it already exists.');
        }

        $this->renderFile('sulu/js/model.js.twig', $modelTarget, $parameters);

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
