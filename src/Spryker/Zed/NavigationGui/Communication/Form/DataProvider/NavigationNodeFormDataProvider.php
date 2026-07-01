<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToLocaleInterface;
use Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface;

class NavigationNodeFormDataProvider
{
    /**
     * @var \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface
     */
    protected $navigationFacade;

    /**
     * @var \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToLocaleInterface
     */
    protected $localeFacade;

    public function __construct(NavigationGuiToNavigationInterface $navigationFacade, NavigationGuiToLocaleInterface $localeFacade)
    {
        $this->navigationFacade = $navigationFacade;
        $this->localeFacade = $localeFacade;
    }

    public function getData(?int $idNavigationNode = null): NavigationNodeTransfer
    {
        $localeCollection = $this->localeFacade->getLocaleCollection();

        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer = $this->setTranslationFields($navigationNodeTransfer, $localeCollection);

        if (!$idNavigationNode) {
            return $navigationNodeTransfer;
        }

        $navigationNodeTransfer->setIdNavigationNode($idNavigationNode);
        $foundNavigationNodeTransfer = $this->navigationFacade->findNavigationNode($navigationNodeTransfer);

        if (!$foundNavigationNodeTransfer) {
            return $navigationNodeTransfer;
        }

        return $this->expandWithMissingLocales($foundNavigationNodeTransfer, $localeCollection);
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeCollection
     */
    protected function setTranslationFields(NavigationNodeTransfer $navigationNodeTransfer, array $localeCollection): NavigationNodeTransfer
    {
        foreach ($localeCollection as $localeTransfer) {
            $navigationNodeLocalizedAttributesTransfer = new NavigationNodeLocalizedAttributesTransfer();
            $navigationNodeLocalizedAttributesTransfer->setFkLocale($localeTransfer->getIdLocale());

            $navigationNodeTransfer->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer);
        }

        return $navigationNodeTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeCollection
     */
    protected function expandWithMissingLocales(NavigationNodeTransfer $navigationNodeTransfer, array $localeCollection): NavigationNodeTransfer
    {
        $activeLocaleIds = $this->indexActiveLocaleIds($localeCollection);

        $navigationNodeTransfer = $this->removeStaleLocalizedAttributes($navigationNodeTransfer, $activeLocaleIds);

        $existingFkLocales = [];
        foreach ($navigationNodeTransfer->getNavigationNodeLocalizedAttributes() as $localizedAttributesTransfer) {
            $existingFkLocales[$localizedAttributesTransfer->getFkLocale()] = true;
        }

        foreach ($localeCollection as $localeTransfer) {
            if (isset($existingFkLocales[$localeTransfer->getIdLocale()])) {
                continue;
            }

            $navigationNodeLocalizedAttributesTransfer = new NavigationNodeLocalizedAttributesTransfer();
            $navigationNodeLocalizedAttributesTransfer->setFkLocale($localeTransfer->getIdLocale());
            $navigationNodeTransfer->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer);
        }

        return $navigationNodeTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeCollection
     *
     * @return array<int, bool>
     */
    protected function indexActiveLocaleIds(array $localeCollection): array
    {
        $activeLocaleIds = [];

        foreach ($localeCollection as $localeTransfer) {
            $activeLocaleIds[$localeTransfer->getIdLocaleOrFail()] = true;
        }

        return $activeLocaleIds;
    }

    /**
     * @param array<int, bool> $activeLocaleIds
     */
    protected function removeStaleLocalizedAttributes(NavigationNodeTransfer $navigationNodeTransfer, array $activeLocaleIds): NavigationNodeTransfer
    {
        $validLocalizedAttributes = new ArrayObject();

        foreach ($navigationNodeTransfer->getNavigationNodeLocalizedAttributes() as $localizedAttributesTransfer) {
            if (!isset($activeLocaleIds[$localizedAttributesTransfer->getFkLocale()])) {
                continue;
            }

            $validLocalizedAttributes->append($localizedAttributesTransfer);
        }

        return $navigationNodeTransfer->setNavigationNodeLocalizedAttributes($validLocalizedAttributes);
    }
}
