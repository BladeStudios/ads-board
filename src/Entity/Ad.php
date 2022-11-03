<?php

namespace App\Entity;

use App\Repository\AdRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 * @ORM\Table(name="ads")
 */
class Ad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 2, max = 255, minMessage = "Nazwa produktu musi zawierać co najmniej {{ limit }} znaki.", maxMessage = "Nazwa produktu może zawierać co najwyżej {{ limit }} znaków.")
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Assert\Regex("/^[1-9]{1}[0-9]*(\.[0-9]{2})?$/", message="Poprawny format ceny to xxx.xx i xxx, np. 12345.67 lub 1234")
     * @Assert\Range(min = 100, max = 1000000000, notInRangeMessage = "Cena musi wynosić od {{ min }} zł do {{ max }} zł.",
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\Length(min = 1, max = 200, minMessage = "Opis produktu musi zawierać co najmniej {{ limit }} znak.", maxMessage = "Opis produktu może zawierać co najwyżej {{ limit }} znaków.")
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
