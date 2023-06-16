<?php

namespace App\Entity;

use App\Entity\Traits\UpdateTrait;
use App\Repository\SneakerRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SneakerRepository::class)]
#[ORM\Index(fields: ['sku'])]
#[ORM\HasLifecycleCallbacks]
class Sneaker
{
    use UpdateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private string $sku;

    #[ORM\Column]
    private string $title;

    #[ORM\Column]
    private string $colorisCode;

    #[ORM\Column]
    private string $colorisName;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(type: Types::INTEGER)]
    private int $retailPrice;

    #[ORM\Column]
    private string $stockXLink;

    /** @var Collection<int, UserSneaker> */
    #[ORM\OneToMany(mappedBy: 'sneaker', targetEntity: UserSneaker::class, orphanRemoval: true)]
    private Collection $userSneakers;

    #[ORM\ManyToOne(targetEntity: Brand::class, cascade: ['persist'], inversedBy: 'sneakers')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Brand $brand;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $dropDate;

    public function __construct()
    {
        $this->userSneakers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Sneaker
     */
    public function setTitle(string $title): Sneaker
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getColorisCode(): string
    {
        return $this->colorisCode;
    }

    /**
     * @param string $colorisCode
     * @return Sneaker
     */
    public function setColorisCode(string $colorisCode): Sneaker
    {
        $this->colorisCode = $colorisCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getColorisName(): string
    {
        return $this->colorisName;
    }

    /**
     * @param string $colorisName
     * @return Sneaker
     */
    public function setColorisName(string $colorisName): Sneaker
    {
        $this->colorisName = $colorisName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Sneaker
     */
    public function setDescription(string $description): Sneaker
    {
        $this->description = $description;
        return $this;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getRetailPrice(): int
    {
        return $this->retailPrice;
    }

    public function setRetailPrice(int $retailPrice): self
    {
        $this->retailPrice = $retailPrice;

        return $this;
    }

    public function getStockXLink(): string
    {
        return $this->stockXLink;
    }

    public function setStockXLink(string $stockXLink): self
    {
        $this->stockXLink = $stockXLink;

        return $this;
    }

    /**
     * @return Collection<int, UserSneaker>
     */
    public function getUserSneakers(): Collection
    {
        return $this->userSneakers;
    }

    public function addUserSneaker(UserSneaker $userSneaker): self
    {
        if (!$this->userSneakers->contains($userSneaker)) {
            $this->userSneakers->add($userSneaker);
            $userSneaker->setSneaker($this);
        }

        return $this;
    }

    public function removeUserSneaker(UserSneaker $userSneaker): self
    {
        if ($this->userSneakers->removeElement($userSneaker)) {
            // set the owning side to null (unless already changed)
            if ($userSneaker->getSneaker() === $this) {
                $userSneaker->setSneaker(null);
            }
        }

        return $this;
    }

    /**
     * @return ?Brand
     */
    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand|null $brand
     * @return Sneaker
     */
    public function setBrand(?Brand $brand): Sneaker
    {
        $this->brand = $brand;
        return $this;
    }

    public function getDropDate(): DateTimeImmutable
    {
        return $this->dropDate;
    }

    public function setDropDate(DateTimeImmutable $dropDate): self
    {
        $this->dropDate = $dropDate;

        return $this;
    }
}
