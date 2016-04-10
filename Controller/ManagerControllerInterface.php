<?php

namespace L91\Sulu\Bundle\BackendBundle\Controller;

use L91\Sulu\Bundle\BackendBundle\Manager\ManagerInterface;
use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineCaseFieldDescriptor;

interface ManagerControllerInterface
{
    /**
     * @return ManagerInterface
     */
    public function getManager();

    /**
     * @param string $locale
     * @param array $filters
     *
     * @return DoctrineCaseFieldDescriptor[]
     */
    public function getFieldDescriptors($locale, $filters);

    /**
     * @return string
     */
    public function getModelClass();

    /**
     * @return string
     */
    public function getListName();
}
