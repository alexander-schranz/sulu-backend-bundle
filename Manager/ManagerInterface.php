<?php

namespace L91\Sulu\Bundle\BackendBundle\Manager;

interface ManagerInterface
{
    /**
     * @param string $id
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
     * @param string $id
     *
     * @return mixed
     */
    public function save($data, $locale = null, $id = null);

    /**
     * @param string $id
     * @param string $locale
     *
     * @return mixed
     */
    public function delete($id, $locale = null);
}
