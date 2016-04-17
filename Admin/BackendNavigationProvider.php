<?php

namespace L91\Sulu\Bundle\BackendBundle\Admin;

use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationItem;
use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationProviderInterface;

class BackendNavigationProvider implements ContentNavigationProviderInterface
{
    /**
     * @var array
     */
    protected $navigationItems;

    /**
     * @param array $navigationItems
     */
    public function __construct(
        $navigationItems
    ) {
        $this->navigationItems = $navigationItems;
    }

    /**
     * {@inheritdoc}
     */
    public function getNavigationItems(array $options = array())
    {
        /** @var ContentNavigationItem[] $navigationItems */
        $navigationItems = [];

        foreach ($this->navigationItems as $key => $navigationItem) {
            $navigationItems[$key] = new ContentNavigationItem($navigationItem['title']);
            $navigationItems[$key]->setAction($navigationItem['action']);
            $navigationItems[$key]->setComponent($navigationItem['component']);
            if (isset($navigationItem['componentOptions'])) {
                $navigationItems[$key]->setComponentOptions($navigationItem['componentOptions']);
            }
        }

        return array_values($navigationItems);
    }
}
