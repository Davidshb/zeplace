<?php

namespace App\Entity;

use App\Entity\Traits\UpdateTrait;
use App\Repository\UserSneakerRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSneakerRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UserSneaker
{
    use UpdateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userSneakers')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userSneakers')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Sneaker $sneaker = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $purchaseDate;

    #[ORM\Column(nullable: true)]
    private ?DateTimeInterface $sellingDate = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $sellingPrice;

    #[ORM\Column(type: Types::INTEGER)]
    private int $purchasePrice;

    #[ORM\Column(type: Types::INTEGER)]
    private int $shippingCost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSneaker(): ?Sneaker
    {
        return $this->sneaker;
    }

    public function setSneaker(?Sneaker $sneaker): self
    {
        $this->sneaker = $sneaker;

        return $this;
    }

    public function getPurchaseDate(): DateTimeInterface
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(DateTimeInterface $purchaseDate): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getSellingDate(): ?DateTimeInterface
    {
        return $this->sellingDate;
    }

    public function setSellingDate(?DateTimeInterface $sellingDate): self
    {
        $this->sellingDate = $sellingDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getSellingPrice(): int
    {
        return $this->sellingPrice;
    }

    /**
     * @param int $sellingPrice
     * @return UserSneaker
     */
    public function setSellingPrice(int $sellingPrice): self
    {
        $this->sellingPrice = $sellingPrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getPurchasePrice(): int
    {
        return $this->purchasePrice;
    }

    /**
     * @param int $purchasePrice
     * @return UserSneaker
     */
    public function setPurchasePrice(int $purchasePrice): self
    {
        $this->purchasePrice = $purchasePrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getShippingCost(): int
    {
        return $this->shippingCost;
    }

    /**
     * @param int $shippingCost
     * @return self
     */
    public function setShippingCost(int $shippingCost): self
    {
        $this->shippingCost = $shippingCost;
        return $this;
    }
}
