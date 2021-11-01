<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\NavigationGui\Communication\Form\DataProvider\NavigationFormDataProvider;
use Spryker\Zed\NavigationGui\Communication\Form\DataProvider\NavigationNodeFormDataProvider;
use Spryker\Zed\NavigationGui\Communication\Form\DeleteNavigationForm;
use Spryker\Zed\NavigationGui\Communication\Form\DeleteNavigationNodeForm;
use Spryker\Zed\NavigationGui\Communication\Form\DuplicateNavigationForm;
use Spryker\Zed\NavigationGui\Communication\Form\NavigationFormType;
use Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeFormType;
use Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeLocalizedAttributesFormType;
use Spryker\Zed\NavigationGui\Communication\Form\UpdateNavigationFormType;
use Spryker\Zed\NavigationGui\Communication\Table\NavigationTable;
use Spryker\Zed\NavigationGui\NavigationGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\NavigationGui\NavigationGuiConfig getConfig()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface getQueryContainer()
 */
class NavigationGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\NavigationGui\Communication\Table\NavigationTable
     */
    public function createNavigationTable()
    {
        return new NavigationTable($this->getQueryContainer());
    }

    /**
     * @deprecated Use {@link getNavigationForm()} instead.
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createNavigationForm(?NavigationTransfer $data = null, array $options = [])
    {
        return $this->getFormFactory()->create($this->createNavigationFormType(), $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getNavigationForm(?NavigationTransfer $data = null, array $options = [])
    {
        return $this->createNavigationForm($data, $options);
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createNavigationFormType()
    {
        return NavigationFormType::class;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDuplicateNavigationForm(?NavigationTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(DuplicateNavigationForm::class, $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteNavigationForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteNavigationForm::class);
    }

    /**
     * @deprecated Use {@link getUpdateNavigationForm()} instead.
     *
     * @param \Generated\Shared\Transfer\NavigationTransfer|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUpdateNavigationForm(?NavigationTransfer $data = null, array $options = [])
    {
        return $this->getFormFactory()->create($this->createUpdateNavigationFormType(), $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getUpdateNavigationForm(?NavigationTransfer $data = null, array $options = [])
    {
        return $this->createUpdateNavigationForm($data, $options);
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createUpdateNavigationFormType()
    {
        return UpdateNavigationFormType::class;
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Communication\Form\DataProvider\NavigationFormDataProvider
     */
    public function createNavigationFormDataProvider()
    {
        return new NavigationFormDataProvider($this->getNavigationFacade());
    }

    /**
     * @deprecated Use {@link getNavigationNodeForm()} instead.
     *
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createNavigationNodeForm(?NavigationNodeTransfer $data = null, array $options = [])
    {
        return $this->getFormFactory()->create($this->createNavigationNodeFormType(), $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getNavigationNodeForm(?NavigationNodeTransfer $data = null, array $options = [])
    {
        return $this->createNavigationNodeForm($data, $options);
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createNavigationNodeFormType()
    {
        return NavigationNodeFormType::class;
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Communication\Form\DataProvider\NavigationNodeFormDataProvider
     */
    public function createNavigationNodeFormDataProvider()
    {
        return new NavigationNodeFormDataProvider($this->getNavigationFacade(), $this->getLocaleFacade());
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteNavigationNodeForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteNavigationNodeForm::class);
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createNavigationNodeLocalizedAttributesFormType()
    {
        return NavigationNodeLocalizedAttributesFormType::class;
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface
     */
    public function getNavigationFacade()
    {
        return $this->getProvidedDependency(NavigationGuiDependencyProvider::FACADE_NAVIGATION);
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(NavigationGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToUrlInterface
     */
    public function getUrlFacade()
    {
        return $this->getProvidedDependency(NavigationGuiDependencyProvider::FACADE_URL);
    }
}
