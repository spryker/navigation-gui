<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface NavigationGuiToLocaleInterface
{
    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale(): LocaleTransfer;

    /**
     * @return array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    public function getLocaleCollection(): array;
}
