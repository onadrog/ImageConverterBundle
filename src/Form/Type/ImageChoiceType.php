<?php

namespace Onadrog\ImageConverterBundle\Form\Type;

use Onadrog\ImageConverterBundle\Service\ImageUtils;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ImageChoiceType extends AbstractType
{
    public function __construct(private AdapterInterface $cache)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined('class');
        $resolver->setDefined('multiple');
    }

    public function getBlockPrefix(): string
    {
        return 'onadrog_imc_entity';
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $entities = $view->vars['choices'];
        $entity_data = [];
        $ids = [];
        foreach ($entities as $item) {
            array_push($entity_data, $item->data);
        }
        $fields_ids = $view->children;
        if (!empty($fields_ids)) {
            foreach ($fields_ids as $item) {
                array_push($ids, ['id' => $item->vars['id'], 'value' => $item->vars['value'], 'name' => $item->vars['full_name']]);
            }
        } else {
            foreach ($entities as $item) {
                array_push($ids, ['id' => $view->vars['id'].'_'.$item->value, 'value' => $item->value, 'name' => $view->vars['full_name']]);
            }
        }

        $view->vars['ids'] = $ids;
        $props = $this->cache->getItem(ImageUtils::CACHE_KEY)->get();
        $view->vars['value'] = $entity_data;

        if ($props['multiple']) {
            $view->vars['multiple'] = $props['multiple'];
        }
        $view->vars['formParent'] = $view->parent->children;
        $view->vars['props'] = $props;
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
