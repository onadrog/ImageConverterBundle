<?php

namespace Onadrog\ImageConverterBundle\Mock\Type;

use Onadrog\ImageConverterBundle\Form\Type\ImageConverterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class SoloType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', ImageConverterType::class);
    }
}
