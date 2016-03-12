<?php

namespace L91\Sulu\Bundle\EventBundle\Manager;

interface ManagerInterface
{
    /**
     * @param string $locale
     * @param array $filters
     *
     * @return array
     */
    public function getFieldDescriptors($locale, $filters);

    /**
     * @param int $id
     * @param string $locale
     *
     * @return mixed
     */
    public function get($id, $locale = null);

    /**
     * @param string $locale
     * @param array $filters
     *
     * @return array
     */
    public function getBy($locale = null, $filters);

    /**
     * @param null $locale
     * @param $filters
     *
     * @return integer
     */
    public function countBy($locale = null, $filters);

    /**
     * @param array $data
     * @param string $locale
     * @param int $id
     *
     * @return mixed
     */
    public function save($data, $locale = null, $id = null);

    /**
     * @param int $id
     * @param string $locale
     *
     * @return mixed
     */
    public function delete($id, $locale = null);

    /**
     * @return string
     */
    public function getModelClass();
}
