<?php

namespace L91\Sulu\Bundle\BackendBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use L91\Sulu\Bundle\BackendBundle\Entity\Repository\BackendRepositoryInterface;

abstract class AbstractBackendManager implements ManagerInterface
{
    /**
     * @return BackendRepositoryInterface
     */
    abstract protected function getRepository();

    /**
     * @return EntityManagerInterface
     */
    abstract protected function getEntityManager();

    /**
     * {@inheritdoc}
     */
    public function findById($id, $locale = null)
    {
        return $this->getRepository()->findById($id, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll($locale = null, $filters)
    {
        return $this->getRepository()->findAll($locale, $filters);
    }

    /**
     * {@inheritdoc}
     */
    public function count($locale = null, $filters)
    {
        return $this->getRepository()->count($locale, $filters);
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

        $this->getEntityManager()->remove($object);
        $this->getEntityManager()->flush();

        return $object;
    }
}
