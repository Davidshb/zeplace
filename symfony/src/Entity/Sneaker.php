<?php

namespace App\Entity;

use App\Entity\Traits\UpdateTrait;
use App\Repository\SneakerRepository;
use DateTimeImmutable;
use DateTimeInterface;
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
    #[ORM\Column(length: 255)]
    private string $uuid;

    #[ORM\Column(length: 100)]
    private string $sku;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $colorisCode;

    #[ORM\Column(length: 255)]
    private string $colorisName;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(type: Types::FLOAT)]
    private float $retailPrice;

    #[ORM\Column(length: 255)]
    private string $stockXLink;

    #[ORM\Column(type: Types::TEXT)]
    private string $imgUrl;

    #[ORM\Column(type: Types::TEXT)]
    private string $thumbnailUrl;

    /** @var Collection<int, UserSneaker> */
    #[ORM\OneToMany(mappedBy: 'sneaker', targetEntity: UserSneaker::class, orphanRemoval: true)]
    private Collection $userSneakers;

    #[ORM\Column(length: 100)]
    private string $brand;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $dropDate;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
        $this->userSneakers = new ArrayCollection();
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku(string $sku): Sneaker
    {
        $this->sku = $sku;
        return $this;
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

    public function getRetailPrice(): float
    {
        return $this->retailPrice;
    }

    public function setRetailPrice(float $retailPrice): self
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
        }

        return $this;
    }

    public function removeUserSneaker(UserSneaker $userSneaker): self
    {
        $this->userSneakers->removeElement($userSneaker);

        return $this;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function getDropDate(): DateTimeImmutable
    {
        return $this->dropDate;
    }

    public function setDropDate(DateTimeInterface $dropDate): self
    {
        $this->dropDate = DateTimeImmutable::createFromInterface($dropDate);

        return $this;
    }

    /**
     * @return string
     */
    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    /**
     * @param string $imgUrl
     * @return Sneaker
     */
    public function setImgUrl(string $imgUrl): Sneaker
    {
        $this->imgUrl = $imgUrl;
        return $this;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getThumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }

    public function setThumbnailUrl(string $thumbnailUrl): Sneaker
    {
        $this->thumbnailUrl = $thumbnailUrl;
        return $this;
    }
}
