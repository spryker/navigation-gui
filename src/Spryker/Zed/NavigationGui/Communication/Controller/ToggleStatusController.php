<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Controller;

use Generated\Shared\Transfer\NavigationCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Will be removed in the next major release.
 * @method \Spryker\Zed\NavigationGui\Communication\NavigationGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface getQueryContainer()
 */
class ToggleStatusController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_ID_NAVIGATION = 'id-navigation';

    /**
     * @var array
     */
    public const MESSAGE_MAP_UPDATE_SUCCESS = [
        true => 'Navigation element %d was activated successfully.',
        false => 'Navigation element %d was deactivated successfully.',
    ];

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idNavigation = $this->castId($request->query->getInt(static::PARAM_ID_NAVIGATION));

        $navigationCriteriaTransfer = (new NavigationCriteriaTransfer())->setIdNavigation($idNavigation);
        $navigationTransfer = $this->getFactory()
            ->getNavigationFacade()
            ->findNavigationByCriteria($navigationCriteriaTransfer);

        if ($navigationTransfer) {
            $navigationTransfer->setIsActive(!$navigationTransfer->getIsActive());

            $this->getFactory()
                ->getNavigationFacade()
                ->updateNavigation($navigationTransfer);

            $this->addSuccessMessage(
                static::MESSAGE_MAP_UPDATE_SUCCESS[$navigationTransfer->getIsActive()],
                ['%d' => $idNavigation],
            );
        } else {
            $this->addErrorMessage('Navigation element %d was not found.', ['%d' => $idNavigation]);
        }

        return $this->redirectResponse('/navigation-gui');
    }
}
