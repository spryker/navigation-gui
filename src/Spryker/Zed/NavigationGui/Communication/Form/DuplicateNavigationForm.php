<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form;

use Generated\Shared\Transfer\NavigationTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\NavigationGui\Communication\NavigationGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\NavigationGui\NavigationGuiConfig getConfig()
 */
class DuplicateNavigationForm extends AbstractType
{
    public const FIELD_ID_BASE_NAVIGATION = 'idNavigation';
    public const FIELD_NAME = 'name';
    public const FIELD_KEY = 'key';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => NavigationTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addNameField($builder)
            ->addKeyField($builder)
            ->addIdBaseNavigationField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_NAME, TextType::class, [
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addKeyField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_KEY, TextType::class, [
                'label' => 'Key',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Callback([
                        'callback' => [$this, 'uniqueKeyCheck'],
                    ]),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdBaseNavigationField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_ID_BASE_NAVIGATION, CheckboxType::class, [
                'label' => 'Active',
                'required' => true,
            ]);

        return $this;
    }

    /**
     * @param string $key
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function uniqueKeyCheck($key, ExecutionContextInterface $context): void
    {
        $navigationTransfer = $context->getRoot()->getData();

        if ($this->hasExistingNavigationKey($key, $this->getIdNavigation($navigationTransfer))) {
            $context->addViolation('Navigation with the same key already exists.');
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer|null $navigationTransfer
     *
     * @return int|null
     */
    protected function getIdNavigation(?NavigationTransfer $navigationTransfer = null): ?int
    {
        if (!$navigationTransfer) {
            return null;
        }

        return $navigationTransfer->getIdNavigation();
    }

    /**
     * @param string $key
     * @param int|null $idNavigation
     *
     * @return bool
     */
    protected function hasExistingNavigationKey($key, $idNavigation = null): bool
    {
        $query = $this->getQueryContainer()
            ->queryNavigation()
            ->filterByKey($key);

        if ($idNavigation) {
            $query->filterByIdNavigation($idNavigation, Criteria::NOT_EQUAL);
        }

        return $query->count() > 0;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'duplicate_navigation';
    }
}
