<?php

namespace Onadrog\ImageConverterBundle\Form\Type;

use Onadrog\ImageConverterBundle\Controller\EntityController;
use Onadrog\ImageConverterBundle\EventSubscriber\ImageConverterSubscriber;
use Onadrog\ImageConverterBundle\Service\ImageUtils;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
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
        private PropertyAccessorInterface $propertyAccessor,
        private AdapterInterface $cache,
        private array $config,
        private EntityController $entityController,
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
        return 'onadrog_image_converter';
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setDataMapper($this)->addEventSubscriber($this->imageConverterSubscriber);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $props = $this->cache->getItem(ImageUtils::CACHE_KEY)->get();
        $imgUrl = null;
        $data = null;
        if ($form['image_converter']->getConfig()->getOption('attr')['data-relation']) {
            $entity = $form['image_converter']->getRoot()->getData();
            $data = $this->propertyAccessor->getValue($entity, $props['form_property']);
        } else {
            $data = $form['image_converter']->getRoot()->getData();
        }
        if (
            isset($props['slug']) &&
            $this->propertyAccessor->isReadable($data, $props['slug']) &&
            null !== $this->propertyAccessor->getValue($data, $props['slug'])
        ) {
            $slug = $this->propertyAccessor->getValue($data, $props['slug']);
            $mime = $this->propertyAccessor->getValue($data, $props['mimeTypes']);
            $imgDimension = $this->propertyAccessor->getValue($data, $props['dimension']);
            $division = $imgDimension['width'] >= 600 ? 4 : 2;
            $view->vars['width'] = $imgDimension['width'] / $division;
            $view->vars['height'] = $imgDimension['height'] / $division;
            $imgUrl = $slug.'.webp';
            $view->vars['original'] = false;
            if (count($mime) > 1) {
                $view->vars['original'] = true;
            }
        }

        $entities = $this->entityController->getEntity($props['entity']);
        $cache_entity = $this->cache->getItem(ImageUtils::ENTITY_CACHE_KEY);
        $cache_entity->set($entities);
        $this->cache->save($cache_entity);

        $view->vars['entity'] = null;
        if ($props['relation']) {
            $view->vars['entity'] = $form->getName();
            $view->vars['value'] = $this->entityController->getEntity($props['entity']);
            $view->vars['props'] = $props;
        }
        $view->vars['use_js'] = $this->config['use_js'];
        $view->vars['image_url'] = $imgUrl;
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
            if ('image_converter' !== $f->getName() && 'original_file' !== $f->getName() && 'entity_value' !== $f->getName()) {
                if (
                    isset($f->getConfig()->getOption('attr')['data-type']) &&
                    'json_array' === $f->getConfig()->getOption('attr')['data-type']
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

        //dd($viewData);
        if ($form['image_converter']->getData()) {
            foreach ($form as $f) {
                if ('image_converter' !== $f->getName() && 'original_file' !== $f->getName()) {
                    if (
                    isset($f->getConfig()->getOption('attr')['data-type']) &&
                    'json_array' === $f->getConfig()->getOption('attr')['data-type']
                    ) {
                        $dataAtrtributes = $f->getConfig()->getOption('attr')['data-prop'];
                        $data = json_decode($f->getData(), true);
                        if ('dimension' === $dataAtrtributes) {
                            $this->propertyAccessor->setValue($viewData, $f->getName(), $data['dimension']);
                        }
                        if ('mimes' === $dataAtrtributes) {
                            $this->propertyAccessor->setValue($viewData, $f->getName(), $data['mimes']);
                        }
                    } else {
                        $this->propertyAccessor->setValue($viewData, $f->getName(), $f->getData());
                    }
                }
            }
        }
        if (isset($form['entity_value']) && null !== $form['entity_value']->getData()) {
            $entities = $this->cache->getItem(ImageUtils::ENTITY_CACHE_KEY)->get();
            foreach ($entities as $item) {
                if ($form['entity_value']->getData() === $this->propertyAccessor->getValue($item, 'id')) {
                    $viewData = $item;
                }
            }
        }
    }
}
