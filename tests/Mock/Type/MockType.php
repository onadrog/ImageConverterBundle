<?php

namespace Onadrog\ImageConverterBundle\Mock\Type;

use Onadrog\ImageConverterBundle\Mock\Entity\Entity\Product;
use Onadrog\ImageConverterBundle\Type\ImageConverterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($options['label'], ImageConverterType::class);

        if (Product::class === $builder->getDataClass()) {
            $builder->add('name');
        }
    }
}
