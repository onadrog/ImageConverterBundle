<?php

namespace Onadrog\ImageConverterBundle\Mock\Type;

use Onadrog\ImageConverterBundle\Type\ImageConverterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('products', ImageConverterType::class);
    }
}
