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

interface NavigationGuiToNavigationInterface
{
    public function createNavigation(NavigationTransfer $navigationTransfer): NavigationTransfer;

    public function updateNavigation(NavigationTransfer $navigationTransfer): NavigationTransfer;

    public function deleteNavigation(NavigationTransfer $navigationTransfer): void;

    public function createNavigationNode(NavigationNodeTransfer $navigationNodeTransfer): NavigationNodeTransfer;

    public function updateNavigationNode(NavigationNodeTransfer $navigationNodeTransfer): NavigationNodeTransfer;

    public function duplicateNavigation(DuplicateNavigationTransfer $duplicateNavigationTransfer): NavigationResponseTransfer;

    public function findNavigationNode(NavigationNodeTransfer $navigationNodeTransfer): ?NavigationNodeTransfer;

    public function deleteNavigationNode(NavigationNodeTransfer $navigationNodeTransfer): void;

    public function findNavigationTree(NavigationTransfer $navigationTransfer, ?LocaleTransfer $localeTransfer = null): NavigationTreeTransfer;

    public function updateNavigationTreeHierarchy(NavigationTreeTransfer $navigationTreeTransfer): void;

    public function findNavigationByCriteria(NavigationCriteriaTransfer $navigationCriteriaTransfer): ?NavigationTransfer;
}
