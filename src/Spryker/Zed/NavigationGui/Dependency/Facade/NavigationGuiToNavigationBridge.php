<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Dependency\Facade;

use Generated\Shared\Transfer\DuplicateNavigationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NavigationCriteriaTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationResponseTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Generated\Shared\Transfer\NavigationTreeTransfer;

class NavigationGuiToNavigationBridge implements NavigationGuiToNavigationInterface
{
    /**
     * @var \Spryker\Zed\Navigation\Business\NavigationFacadeInterface
     */
    protected $navigationFacade;

    /**
     * @param \Spryker\Zed\Navigation\Business\NavigationFacadeInterface $navigationFacade
     */
    public function __construct($navigationFacade)
    {
        $this->navigationFacade = $navigationFacade;
    }

    public function createNavigation(NavigationTransfer $navigationTransfer): NavigationTransfer
    {
        return $this->navigationFacade->createNavigation($navigationTransfer);
    }

    public function updateNavigation(NavigationTransfer $navigationTransfer): NavigationTransfer
    {
        return $this->navigationFacade->updateNavigation($navigationTransfer);
    }

    public function deleteNavigation(NavigationTransfer $navigationTransfer): void
    {
        $this->navigationFacade->deleteNavigation($navigationTransfer);
    }

    public function createNavigationNode(NavigationNodeTransfer $navigationNodeTransfer): NavigationNodeTransfer
    {
        return $this->navigationFacade->createNavigationNode($navigationNodeTransfer);
    }

    public function updateNavigationNode(NavigationNodeTransfer $navigationNodeTransfer): NavigationNodeTransfer
    {
        return $this->navigationFacade->updateNavigationNode($navigationNodeTransfer);
    }

    public function duplicateNavigation(DuplicateNavigationTransfer $duplicateNavigationTransfer): NavigationResponseTransfer
    {
        return $this->navigationFacade->duplicateNavigation($duplicateNavigationTransfer);
    }

    public function findNavigationNode(NavigationNodeTransfer $navigationNodeTransfer): ?NavigationNodeTransfer
    {
        return $this->navigationFacade->findNavigationNode($navigationNodeTransfer);
    }

    public function deleteNavigationNode(NavigationNodeTransfer $navigationNodeTransfer): void
    {
        $this->navigationFacade->deleteNavigationNode($navigationNodeTransfer);
    }

    public function findNavigationTree(NavigationTransfer $navigationTransfer, ?LocaleTransfer $localeTransfer = null): NavigationTreeTransfer
    {
        return $this->navigationFacade->findNavigationTree($navigationTransfer, $localeTransfer);
    }

    public function updateNavigationTreeHierarchy(NavigationTreeTransfer $navigationTreeTransfer): void
    {
        $this->navigationFacade->updateNavigationTreeHierarchy($navigationTreeTransfer);
    }

    public function findNavigationByCriteria(NavigationCriteriaTransfer $navigationCriteriaTransfer): ?NavigationTransfer
    {
        return $this->navigationFacade->findNavigationByCriteria($navigationCriteriaTransfer);
    }
}
