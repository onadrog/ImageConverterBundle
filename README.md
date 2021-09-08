# ImageConverterBundle

Convert your images into WebP format using the symfony form Component.

### Requirements

#### Prod:

- PHP >= 8.0
- [GDImage](https://www.php.net/manual/en/intro.image.php)

#### Dev:

- [Docker](https://docs.docker.com/)

### Installation:

```bash
composer require onadrog/imageconverterbundle
```

MakeFile:

```bash
# Run phpunit tests
Make tests

# Run phpstan
make phpstan

# Run php-cs-fixer
make fixer

# Run the test env console command
make console
(e.g: make console cache:clear)

# Build docker image
make dockerbuild

# Remove docker image
make dockerrm
```

```php
// src/Entity/Foo.php
namespace App\Entity;

// ...
use Onadrog\ImageConverterBundle\Mapping\Attribute as Onadrog;

#[Onadrog\ImageUpload]
class Foo
{
    // ...

    private ?string imageName;

    private ?string imageSlug;

    /**
     * @ORM\Column(type="json")
     */
    private ?array imageDimension = [];

    private ?string imageAlt;

    #[Onadrog\ImageUploadProperties(name: 'imageName', slug: 'imageSlug', alt: 'imageAlt', dimension: 'imageDimension')]
    private file;
}
```

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

```php
// src/Entity/Product.php

namespace App\Entity;

// ...

class Product
{
    //...

    /**
     * @ORM\ManyToOne(targetEntity=Media::class, inversedBy="products", cascade={"persist"})
     */
    private $media;
}

// src/Entity/Media.php

namespace App\Entity;

use Onadrog\ImageConverterBundle\Mapping\Attribute as Onadrog;

#[Onadrog\ImageUpload]
class Media
{
    // ...

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $slug;

    /**
     * @ORM\Column(type="json")
     */
    private ?array $dimension = [];

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="media")
     */
    private $products;

    #[Onadrog\ImageUploadProperties(name: 'name', slug: 'slug', dimension: 'dimension', alt: 'alt')]
    private $file;
}

```

```php
// src/Form/ProductType.php

namespace App\Foo\Form;

// ...
use Onadrog\ImageConverterBundle\Form\Type\ImageConverterType;

class ProductType extends AbstractType
{
    //..
    public function builForm(FormBuilder $builder, array $options)
    {
        // ...

        $builder->add('media', ImageConverterType::class);
    }
}
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
```

## Options:

### `media_uploads_path:`

Type: `string`

The absolute path where the images will be stored.

### `namer:`

Type: `string`

Possibles values: `'default' , 'mixed', 'uuid'`

How the image name will be stored in the database.

Examples:

Using `redCar.png` as reference will return.

`default`: redCar.webp

`mixed`: redCar-61387ca1ddbb15-68157544.webp

`uuid`: 1ec0f1d9-d211-6422-9082-9bb075af7050.webp

### `quality:`

Type: `int`

min: `0`, max: `100`

Compression factor for the image. Smaller value: smaller file size but lower quality, higher value: higher file size but better quality.

#### `public_path:`

Type: `string`

The path relative to the public directory of the website where the images will be stored.

### `remove_orphans:`

Type: `bool`

On update will detele the previous stored image.

# [Read the Docs](https://github.com/onadrog/ImageConverterBundle/wiki)

# Donate:

Eth address: `0xB374931cc925042731d971C07708be68B115BC0d`

![Qr_code](img/eth_qr.png)
