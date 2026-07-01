<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\NavigationGui\Communication\Form\DataProvider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Spryker\Zed\NavigationGui\Communication\Form\DataProvider\NavigationNodeFormDataProvider;
use Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToLocaleInterface;
use Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group NavigationGui
 * @group Communication
 * @group Form
 * @group DataProvider
 * @group NavigationNodeFormDataProviderTest
 * Add your own group annotations below this line
 */
class NavigationNodeFormDataProviderTest extends Unit
{
    protected const ID_LOCALE_EN = 1;

    protected const ID_LOCALE_DE = 2;

    protected const ID_LOCALE_FR = 3;

    public function testGetDataForNewNodeCreatesOnlyActiveLocaleSlots(): void
    {
        $dataProvider = new NavigationNodeFormDataProvider(
            $this->createNavigationFacadeMock(),
            $this->createLocaleFacadeMock([static::ID_LOCALE_EN, static::ID_LOCALE_DE]),
        );

        $result = $dataProvider->getData();

        $localizedAttributeLocaleIds = $this->extractLocaleIds($result);
        $this->assertContains(static::ID_LOCALE_EN, $localizedAttributeLocaleIds);
        $this->assertContains(static::ID_LOCALE_DE, $localizedAttributeLocaleIds);
        $this->assertNotContains(static::ID_LOCALE_FR, $localizedAttributeLocaleIds);
        $this->assertCount(2, $localizedAttributeLocaleIds);
    }

    public function testGetDataForExistingNodeRemovesStaleLocalizedAttributes(): void
    {
        $existingNodeTransfer = $this->createNavigationNodeTransferWithLocales([
            static::ID_LOCALE_EN,
            static::ID_LOCALE_DE,
            static::ID_LOCALE_FR,
        ]);

        $dataProvider = new NavigationNodeFormDataProvider(
            $this->createNavigationFacadeMock($existingNodeTransfer),
            $this->createLocaleFacadeMock([static::ID_LOCALE_EN, static::ID_LOCALE_DE]),
        );

        $result = $dataProvider->getData(1);

        $localizedAttributeLocaleIds = $this->extractLocaleIds($result);
        $this->assertContains(static::ID_LOCALE_EN, $localizedAttributeLocaleIds);
        $this->assertContains(static::ID_LOCALE_DE, $localizedAttributeLocaleIds);
        $this->assertNotContains(static::ID_LOCALE_FR, $localizedAttributeLocaleIds);
        $this->assertCount(2, $localizedAttributeLocaleIds);
    }

    public function testGetDataForExistingNodeAddsNewLocaleSlot(): void
    {
        $existingNodeTransfer = $this->createNavigationNodeTransferWithLocales([
            static::ID_LOCALE_EN,
            static::ID_LOCALE_DE,
        ]);

        $dataProvider = new NavigationNodeFormDataProvider(
            $this->createNavigationFacadeMock($existingNodeTransfer),
            $this->createLocaleFacadeMock([static::ID_LOCALE_EN, static::ID_LOCALE_DE, static::ID_LOCALE_FR]),
        );

        $result = $dataProvider->getData(1);

        $localizedAttributeLocaleIds = $this->extractLocaleIds($result);
        $this->assertContains(static::ID_LOCALE_EN, $localizedAttributeLocaleIds);
        $this->assertContains(static::ID_LOCALE_DE, $localizedAttributeLocaleIds);
        $this->assertContains(static::ID_LOCALE_FR, $localizedAttributeLocaleIds);
        $this->assertCount(3, $localizedAttributeLocaleIds);
    }

    public function testGetDataForExistingNodePreservesExistingAttributeData(): void
    {
        $existingNodeTransfer = $this->createNavigationNodeTransferWithLocales([static::ID_LOCALE_EN]);
        $existingNodeTransfer->getNavigationNodeLocalizedAttributes()->offsetGet(0)->setTitle('Test title');

        $dataProvider = new NavigationNodeFormDataProvider(
            $this->createNavigationFacadeMock($existingNodeTransfer),
            $this->createLocaleFacadeMock([static::ID_LOCALE_EN]),
        );

        $result = $dataProvider->getData(1);

        $this->assertSame('Test title', $result->getNavigationNodeLocalizedAttributes()->offsetGet(0)->getTitle());
    }

    protected function createNavigationFacadeMock(?NavigationNodeTransfer $returnTransfer = null): NavigationGuiToNavigationInterface
    {
        $mock = $this->createMock(NavigationGuiToNavigationInterface::class);
        $mock->method('findNavigationNode')->willReturn($returnTransfer);

        return $mock;
    }

    /**
     * @param array<int> $idLocales
     */
    protected function createLocaleFacadeMock(array $idLocales): NavigationGuiToLocaleInterface
    {
        $localeTransfers = array_map(static function (int $idLocale): LocaleTransfer {
            return (new LocaleTransfer())->setIdLocale($idLocale);
        }, $idLocales);

        $mock = $this->createMock(NavigationGuiToLocaleInterface::class);
        $mock->method('getLocaleCollection')->willReturn($localeTransfers);

        return $mock;
    }

    /**
     * @param array<int> $idLocales
     */
    protected function createNavigationNodeTransferWithLocales(array $idLocales): NavigationNodeTransfer
    {
        $navigationNodeTransfer = new NavigationNodeTransfer();

        foreach ($idLocales as $idLocale) {
            $navigationNodeTransfer->addNavigationNodeLocalizedAttribute(
                (new NavigationNodeLocalizedAttributesTransfer())->setFkLocale($idLocale),
            );
        }

        return $navigationNodeTransfer;
    }

    /**
     * @return array<int>
     */
    protected function extractLocaleIds(NavigationNodeTransfer $navigationNodeTransfer): array
    {
        $localeIds = [];

        foreach ($navigationNodeTransfer->getNavigationNodeLocalizedAttributes() as $localizedAttributesTransfer) {
            $localeIds[] = $localizedAttributesTransfer->getFkLocale();
        }

        return $localeIds;
    }
}
