# ImageConverterBundle

Convert your images into WebP format using the symfony form Component and Doctrine bundle to persist data.

### Requirements

#### Prod:

- PHP >= 8.0
- [GDImage](https://www.php.net/manual/en/intro.image.php)

### Installation:

```bash
composer require onadrog/imageconverterbundle
php bin/console asset:install
```

## Minimal configuration

This package use php8 attributes.

Use `ImageUpload` and `ImageUploadProperties` attributes as follow.

```php
// src/Entity/Foo.php
namespace App\Entity;

// ...
use Onadrog\ImageConverterBundle\Mapping\Attribute as Onadrog;

/**
 * @ORM\Entity(repositoryClass=FooRepository::class)
 */
#[Onadrog\ImageUpload]
class Foo
{
    // ...

    private $fileName;

    private $fileSlug;

    /**
     * @ORM\Column(type="json")
     */
    private ?array $fileDimension = [];

     /**
     * @ORM\Column(type="json")
     */
    private ?array $mimeTypes = [];

    private  $fileAlt;

    #[Onadrog\ImageUploadProperties(name: 'fileName', slug: 'fileSlug', alt: 'fileAlt', dimension: 'fileDimension', mimeTypes: 'mimeTypes')]
    private $file;
}
```

Once done use the `ImageConverterType` in your FormBuilder related to the property where the `ImageUploadProperties` is mapped (here `file`) .

```php
// src/Form/FooType.php

namespace App\Foo\Form;

// ...
use Onadrog\ImageConverterBundle\Form\Type\ImageConverterType;

class FooType extends AbstractType
{
    //..
    public function builForm(FormBuilder $builder, array $options)
    {
        // ...

        $builder->add('file', ImageConverterType::class);
    }
}
```

## Twig extension:

```twig
{% extends 'base.html.twig' %}

{% block body %}
    {{ image_converter_img(foo) }}
{% endblock %}
```

## Configuration default values:

```yaml
# config/packages/image_converter.yaml

image_converter:
  media_uploads_path: "%kernel.project_dir%/public/uploads/media/"
  namer: "default"
  quality: 80
  public_path: "/uploads/media/"
  remove_orphans: true
  keep_original: false
  use_js: false
```

## Console commands:

```bash
onadrog:debug:config
onadrog:dump:config
onadrog:make:entity [options] <args>
```

# For more informations read the [Wiki](https://github.com/onadrog/ImageConverterBundle/wiki)

# Donate:

Eth address: `0xB374931cc925042731d971C07708be68B115BC0d`

![Qr_code](img/eth_qr.png)
