<?php

namespace Onadrog\ImageConverterBundle\Twig;

use Onadrog\ImageConverterBundle\Service\ImageUtils;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ImageTwigExtension extends AbstractExtension
{
    public function __construct(private Environment $env)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'image_converter_img',
                [$this, 'addPicture'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function addPicture(
        array|object $value,
        bool $lazyLoad = true,
        string $classname = 'image_converter_img',
        string $property = 'file'
    ): string {
        if (!is_array($value)) {
            $value = [$value];
        }
        foreach ($value as $obj) {
            $props = ImageUtils::guessMappedClass($obj, $property);
            $render = $this->env->render('@ImageConverter/image_converter_picture.html.twig', [
            'props' => $props,
            'value' => $value,
            'lazyLoad' => $lazyLoad,
            'classname' => $classname,
            ]);
        }

        return $render;
    }
}
