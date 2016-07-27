<?php

namespace L91\Sulu\Bundle\BackendBundle\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use L91\Sulu\Bundle\BackendBundle\Entity\BackendRepositoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractBackendManager implements ManagerInterface
{
    /**
     * @var BackendRepositoryInterface
     */
    protected $repository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * EventManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ObjectRepository $repository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectRepository $repository
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function findById($id, $locale = null)
    {
        return $this->repository->findById($id, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll($locale, $filters)
    {
        return $this->repository->findAll($locale, $filters);
    }

    /**
     * {@inheritdoc}
     */
    public function count($locale, $filters)
    {
        return $this->repository->count($locale, $filters);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id, $locale = null)
    {
        $object = $this->findById($id, $locale);

        if (!$object) {
            return;
        }

        $this->entityManager->remove($object);
        $this->entityManager->flush();

        return $object;
    }

    /**
     * @param $data
     * @param $value
     * @param mixed $default
     * @param string $type
     *
     * @return mixed
     */
    protected static function getValue($data, $value, $default = null, $type = null)
    {
        $value = PropertyAccess::createPropertyAccessor()->getValue($data, '[' . $value . ']');

        if (!$value) {
            return $default;
        }

        if ($type === 'date') {
            return new \DateTime($value);
        }

        if ($type === 'number') {
            return (int) $value;
        }

        if ($type === 'decimal') {
            return (float) $value;
        }

        return $value;
    }
}
