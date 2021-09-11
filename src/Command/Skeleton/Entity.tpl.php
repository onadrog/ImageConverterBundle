<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $repository_full_class_name; ?>;
use Doctrine\ORM\Mapping as ORM;
use Onadrog\ImageConverterBundle\Mapping\Attribute as Onadrog;

/**
 * @ORM\Entity(repositoryClass=<?php echo $repository_class_name; ?>::class)
 */
#[Onadrog\ImageUpload]
class <?php echo $class_name."\n"; ?>
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="string", length=100)
    */
    private $<?php echo $name; ?>;

    /**
    * @ORM\Column(type="json")
    */
    private $<?php echo $dimension; ?> = [];

    /**
    * @ORM\Column(type="json")
    */
    private $mimeTypes = [];

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $<?php echo $slug; ?>;

    /**
    * @ORM\Column(type="string", length=150)
    */
    private $<?php echo $alt; ?>;

    #[Onadrog\ImageUploadProperties(name: '<?php echo $name; ?>', slug: '<?php echo $slug; ?>', alt: '<?php echo $alt; ?>', dimension: '<?php echo $dimension; ?>', mimeTypes: 'mimeTypes')]
    private $file;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function get<?php echo ucfirst($name); ?>(): ?string
    {
        return $this-><?php echo $name; ?>;
    }

    public function get<?php echo ucfirst($dimension); ?>(): ?array
    {
        return $this-><?php echo $dimension; ?>;
    }
    public function getMimeTypes(): ?array
    {
        return $this->mimeTypes;
    }

    public function get<?php echo ucfirst($slug); ?>(): ?string
    {
        return $this-><?php echo $slug; ?>;
    }

    public function get<?php echo ucfirst($alt); ?>(): ?string
    {
        return $this-><?php echo $alt; ?>;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function set<?php echo ucfirst($name); ?>(string $<?php echo $name; ?>): self
    {
        $this-><?php echo $name; ?> = $<?php echo $name; ?>;
        return $this;
    }

    public function set<?php echo ucfirst($dimension); ?>(array $<?php echo $dimension; ?>): self
    {
        $this-><?php echo $dimension; ?> = $<?php echo $dimension; ?>;
        return $this;
    }
    public function setMimeTypes(array $mimeTypes): self
    {
        $this->mimeTypes = $mimeTypes;;
        return $this;
    }

    public function set<?php echo ucfirst($slug); ?>(string $<?php echo $slug; ?>): self
    {
        $this-><?php echo $slug; ?> = $<?php echo $slug; ?>;
        return $this;
    }

    public function set<?php echo ucfirst($alt); ?>(string $<?php echo $alt; ?>): self
    {
        $this-><?php echo $alt; ?> = $<?php echo $alt; ?>;
        return $this;
    }

     public function SetFile($file)
    {
        $this->file = $file;
        return $this;
    }
}