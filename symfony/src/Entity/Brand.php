<?php

namespace App\Entity;

use App\Entity\Traits\UpdateTrait;
use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Brand
{
    use UpdateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'brands', targetEntity: Sneaker::class)]
    private Collection $sneakers;

    public function __construct()
    {
        $this->sneakers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Brand
     */
    public function setName(string $name): Brand
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<Sneaker>
     */
    public function getSneakers(): Collection
    {
        return $this->sneakers;
    }

    public function addSneaker(Sneaker $sneaker): self
    {
        if (!$this->sneakers->contains($sneaker)) {
            $this->sneakers->add($sneaker);
            $sneaker->setBrand($this);
        }

        return $this;
    }

    public function removeSneaker(Sneaker $sneaker): self {
        if($this->sneakers->removeElement($sneaker)) {
            $sneaker->setBrand(null);
        }

        return $this;
    }
}