<?php

namespace Onadrog\ImageConverterBundle\Type;

use Onadrog\ImageConverterBundle\EventSubscriber\ImageConverterSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Traversable;

class ImageConverterType extends AbstractType implements DataMapperInterface
{
    public function __construct(
        private ImageConverterSubscriber $imageConverterSubscriber
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
        if (null === $viewData) {
            return;
        }

        /** @var FormInterface[] $form */
        $form = iterator_to_array($forms);
        foreach ($form as $f) {
            if ('image_converter' !== $f->getName()) {
                $class = $f->getParent()->getData();
                $getter = $this->getPropertyAccess()->getValue($class, $f->getName());
                $f->setData($viewData->$getter);
            }
        }
    }

    public function mapFormsToData(Traversable $forms, &$viewData): void
    {
        /** @var FormInterface[] $form */
        $form = iterator_to_array($forms);
        foreach ($form as $f) {
            if ('image_converter' !== $f->getName()) {
                $class = $f->getRoot()->getData();
                if ('dimension' === $f->getName()) {
                    $data[] = json_decode($f->getData(), true);
                    $setter = $this->getPropertyAccess()->setValue($class, $f->getName(), $data[0]);
                } else {
                    $setter = $this->getPropertyAccess()->setValue($class, $f->getName(), $f->getData());
                }
                $viewData = $setter;
            }
        }
    }

    private function getPropertyAccess(): PropertyAccessor
    {
        return PropertyAccess::createPropertyAccessor();
    }
}
