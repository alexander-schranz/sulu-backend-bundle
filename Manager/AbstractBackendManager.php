<?php

namespace L91\Sulu\Bundle\BackendBundle\Manager;

use L91\Sulu\Bundle\BackendBundle\Entity\Repository\BackendRepositoryInterface;

abstract class AbstractBackendManager implements ManagerInterface
{
    /**
     * @return BackendRepositoryInterface
     */
    abstract protected function getRepository();

    /**
     * {@inheritdoc}
     */
    public function get($id, $locale = null)
    {
        return $this->getRepository()->get($id, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getBy($locale = null, $filters)
    {
        return $this->getRepository()->get($locale, $filters);
    }

    /**
     * {@inheritdoc}
     */
    public function countBy($locale = null, $filters)
    {
        return $this->getRepository()->countBy($locale, $filters);
    }
}
