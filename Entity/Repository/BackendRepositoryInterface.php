<?php

namespace L91\Sulu\Bundle\BackendBundle\Entity\Repository;

interface BackendRepositoryInterface
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
    public function getBy($locale = null, $filters = []);

    /**
     * @param string $locale
     * @param array $filters
     *
     * @return int
     */
    public function countBy($locale = null, $filters = []);
}