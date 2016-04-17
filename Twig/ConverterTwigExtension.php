<?php

namespace L91\Sulu\Bundle\BackendBundle\Twig;

class ConverterTwigExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('snake_case', [$this, 'snakeCaseFilter']),
            new \Twig_SimpleFilter('camelize', 'lcfirst'),
        ];
    }

    /**
     * @param $input
     * @param string $glue
     *
     * @return mixed
     */
    public function snakeCaseFilter($input, $glue = '_')
    {
        return str_replace('_', $glue, ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $input)), '_'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'l91_sulu_backend_converter';
    }
}
