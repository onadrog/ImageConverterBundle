<?php

namespace Onadrog\ImageConverterBundle\Type;

use Onadrog\ImageConverterBundle\EventSubscriber\ImageConverterSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Traversable;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ImageConverterType extends AbstractType implements DataMapperInterface
{
    public function __construct(
        private ImageConverterSubscriber $imageConverterSubscriber,
        private PropertyAccessorInterface $propertyAccessor
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'image_converter';
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setDataMapper($this)->addEventSubscriber($this->imageConverterSubscriber);
    }

    public function mapDataToForms($viewData, Traversable $forms): void
    {
        /** @var FormInterface[] $form */
        $form = iterator_to_array($forms);
        if ($form['image_converter']->getConfig()->getOption('attr')['data-relation']) {
            $class = $form['image_converter']->getConfig()->getOption('attr')['data-entity'];
            $viewData = new $class();
        } else {
            $viewData = $form['image_converter']->getRoot()->getData();
        }
        if (null === $viewData) {
            return;
        }
        foreach ($form as $f) {
            if ('image_converter' !== $f->getName()) {
                if (
                    isset($f->getConfig()->getOption('attr')['data-type']) &&
                    'dimension' === $f->getConfig()->getOption('attr')['data-type']
                ) {
                    $val = $this->propertyAccessor->getValue($viewData, $f->getName());
                    $f->setData(json_encode($val));
                } else {
                    $val = $this->propertyAccessor->getValue($viewData, $f->getName());
                    $f->setData($val);
                }
            }
        }
    }

    public function mapFormsToData(Traversable $forms, &$viewData): void
    {
        /** @var FormInterface[] $form */
        $form = iterator_to_array($forms);
        if ($form['image_converter']->getConfig()->getOption('attr')['data-relation']) {
            $class = $form['image_converter']->getConfig()->getOption('attr')['data-entity'];
            $viewData = new $class();
        } else {
            $viewData = $form['image_converter']->getRoot()->getData();
        }

        foreach ($form as $f) {
            if ('image_converter' !== $f->getName()) {
                if (
                    isset($f->getConfig()->getOption('attr')['data-type']) &&
                    'dimension' === $f->getConfig()->getOption('attr')['data-type']
                ) {
                    $data[] = json_decode($f->getData(), true);
                    $this->propertyAccessor->setValue($viewData, $f->getName(), $data[0]);
                } else {
                    $this->propertyAccessor->setValue($viewData, $f->getName(), $f->getData());
                }
            }
        }
    }
}
