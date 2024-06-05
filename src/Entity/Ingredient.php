<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
/*
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
    /**
     * Ingredient constructor.
     * set the createdAt property to the current date and time when a new Ingredient object is created.
 */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\Unique(message: "This name already exists")]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "The name must be at least {{ limit }} characters long",
        maxMessage: "The name cannot be longer than {{ limit }} characters"
    )]
    private string $name;

    #[ORM\Column]
    #[Assert\NotNull(message: "The price cannot be null")]
    #[Assert\Positive(message: "The price must be a positive number")]
    #[Assert\LessThanOrEqual(
        value: 200,
        message: "The price cannot be greater than {{ compared_value }}"
    )]
    private float $price;

    #[ORM\Column]
    #[Assert\NotNull(message: "The createdAt date cannot be null")]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

}
