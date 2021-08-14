<?php

namespace Onadrog\ImageConverterBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ImageTwigExtension extends AbstractExtension
{
    public function __construct(private array $config)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('image_converter_picture', [$this, 'addPicture'], ['is_safe' => ['html']]),
            new TwigFunction('image_converter_uri', [$this, 'getUri'], ['is_safe' => ['html']]),
        ];
    }

    public function addPicture(string $value): string
    {
        return <<<HTML
        <picture></picture>
        HTML;
    }

    public function getUri(string $value): string
    {
        return $this->config['media_uploads_path'].$value;
    }
}
