<?php


namespace L91\Sulu\Bundle\BackendBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Navigation\Navigation;
use Sulu\Bundle\AdminBundle\Navigation\NavigationItem;
use Sulu\Component\Security\Authorization\SecurityCheckerInterface;

class BackendAdmin extends Admin
{
    /**
     * @var SecurityCheckerInterface
     */
    private $securityChecker;

    /**
     * @var string
     */
    private $jsBundleName;

    /**
     * @var array
     */
    private $securityContexts;

    public function __construct(
        SecurityCheckerInterface $securityChecker,
        $title,
        $navigationTitle,
        $navigationSymbol,
        $jsBundleName,
        $children = [],
        $securityContexts = []
    ) {
        $this->securityChecker = $securityChecker;
        $this->jsBundleName = $jsBundleName;
        $this->securityContexts = $securityContexts;

        // set root navigation
        $rootNavigationItem = new NavigationItem($title);

        // parent navigation
        $section = new NavigationItem('');

        // create section
        $navigationItem = new NavigationItem($navigationTitle);
        $navigationItem->setIcon($navigationSymbol);
        $section->addChild($navigationItem);

        // add child navigation
        foreach ($children as $child) {
            if ($this->securityChecker->hasPermission($child['permission'], 'view')) {
                $childItem = new NavigationItem($child['title']);
                $childItem->setIcon($child['icon']);
                $childItem->setAction($child['action']);
                $navigationItem->addChild($childItem);
            }
        }

        // add section only when exist
        if ($navigationItem->hasChildren()) {
            $rootNavigationItem->addChild($section);
        }

        // set navigation
        $this->setNavigation(new Navigation($rootNavigationItem));
    }

    /**
     * {@inheritdoc}
     */
    public function getCommands()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getJsBundleName()
    {
        return $this->jsBundleName;
    }

    /**
     * {@inheritDoc}
     */
    public function getSecurityContexts()
    {
        return $this->securityContexts;
    }
}
