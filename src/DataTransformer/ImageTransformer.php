<?php

namespace Onadrog\ImageConverterBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class ImageTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return [
            'image' => $value,
        ];
    }

    public function reverseTransform($value)
    {
        return $value['image'];
    }
}
