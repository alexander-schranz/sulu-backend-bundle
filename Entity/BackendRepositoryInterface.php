<?php

namespace L91\Sulu\Bundle\BackendBundle\Entity;

interface BackendRepositoryInterface
{
    /**
     * @param string $id
     * @param string $locale
     *
     * @return mixed
     */
    public function findById($id, $locale = null);

    /**
     * @param string $locale
     * @param array $filters
     *
     * @return array
     */
    public function findAll($locale = null, $filters = []);

    /**
     * @param string $locale
     * @param array $filters
     *
     * @return int
     */
    public function count($locale = null, $filters = []);
}
