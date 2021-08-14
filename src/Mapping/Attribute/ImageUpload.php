<?php

namespace Onadrog\ImageConverterBundle\Mapping\Attribute;

use Attribute;
use Onadrog\ImageConverterBundle\Mapping\AnnotationInterface;

#[Attribute(Attribute::TARGET_CLASS)]
class ImageUpload implements AnnotationInterface
{
}
