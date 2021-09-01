<?php

namespace Onadrog\ImageConverterBundle\Twig;

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
                'image_converter_picture',
                [$this, 'addPicture'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function addPicture(
        object $value,
        bool $lazyLoad = true,
        string $classname = 'image_converter_picture'
    ): string {
        return $this->env->render('image_converter_picture.html.twig', [
            'value' => $value,
            'lazyLoad' => $lazyLoad,
            'classname' => $classname,
        ]);
        /* return <<<HTML
        <picture class="$classname"><img src=$value.slug alt=$value.name width=$value.dimension.width height=$value.dimension.height/></picture>
        HTML; */
    }
}
