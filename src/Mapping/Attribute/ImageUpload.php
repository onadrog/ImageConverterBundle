<?php

namespace Onadrog\ImageConverterBundle\Mapping\Attribute;

use Attribute;
use Onadrog\ImageConverterBundle\Mapping\AnnotationInterface;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ImageUpload implements AnnotationInterface
{
}
