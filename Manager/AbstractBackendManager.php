<?php

namespace L91\Sulu\Bundle\BackendBundle\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use L91\Sulu\Bundle\BackendBundle\Entity\BackendRepositoryInterface;

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
    public function findAll($locale = null, $filters)
    {
        return $this->repository->findAll($locale, $filters);
    }

    /**
     * {@inheritdoc}
     */
    public function count($locale = null, $filters)
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
            return null;
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
        if (isset($data[$value])) {
            if ($type === 'date') {
                return new \DateTime($data[$value]);
            }

            return $data[$value];
        }

        return $default;
    }
}
