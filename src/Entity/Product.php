<?php

namespace App\Entity;

use App\Entity\Utils\DateTimeTrait;
use App\Entity\Utils\EnableTrait;
use App\Entity\Utils\SluggableTrait;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    use SluggableTrait;
    use EnableTrait;
    use DateTimeTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $authenticity = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gender $gender = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Model $model = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Marque $marque = null;

    /**
     * @var Collection<int, ProductVariant>
     */
    #[ORM\OneToMany(targetEntity: ProductVariant::class, mappedBy: 'products')]
    private Collection $productVariants;

    public function __construct()
    {
        $this->productVariants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthenticity(): ?string
    {
        return $this->authenticity;
    }

    public function setAuthenticity(?string $authenticity): static
    {
        $this->authenticity = $authenticity;

        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * @return Collection<int, ProductVariant>
     */
    public function getProductVariants(): Collection
    {
        return $this->productVariants;
    }

    public function addProductVariant(ProductVariant $productVariant): static
    {
        if (!$this->productVariants->contains($productVariant)) {
            $this->productVariants->add($productVariant);
            $productVariant->setProducts($this);
        }

        return $this;
    }

    public function removeProductVariant(ProductVariant $productVariant): static
    {
        if ($this->productVariants->removeElement($productVariant)) {
            // set the owning side to null (unless already changed)
            if ($productVariant->getProducts() === $this) {
                $productVariant->setProducts(null);
            }
        }

        return $this;
    }
}
