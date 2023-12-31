<?php

namespace App\Entity;

use App\Entity\Traits\UpdateTrait;
use App\Enum\ShoeSize;
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
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userSneakers')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\ManyToOne(inversedBy: 'userSneakers')]
    #[ORM\JoinColumn(name: 'sneaker_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    private Sneaker $sneaker;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private DateTimeInterface $purchaseDate;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $sellingDate = null;

    #[ORM\Column(type: Types::FLOAT)]
    private float $sellingPrice = 0;

    #[ORM\Column(type: Types::FLOAT)]
    private float $purchasePrice = 0;

    #[ORM\Column(type: Types::FLOAT)]
    private float $shippingCost = 0;

    #[ORM\Column(type: Types::TEXT)]
    private string $comment = '';

    #[ORM\Column(type: Types::STRING, length: 10, enumType: ShoeSize::class)]
    private ShoeSize $size;

    public function __construct(User $user, Sneaker $sneaker)
    {
        $this->user = $user;
        $this->sneaker = $sneaker;
        $user->addUserSneaker($this);
        $sneaker->addUserSneaker($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getSneaker(): ?Sneaker
    {
        return $this->sneaker;
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
     * @return float
     */
    public function getSellingPrice(): float
    {
        return $this->sellingPrice;
    }

    /**
     * @param float $sellingPrice
     * @return UserSneaker
     */
    public function setSellingPrice(float $sellingPrice): self
    {
        $this->sellingPrice = $sellingPrice;
        return $this;
    }

    /**
     * @return float
     */
    public function getPurchasePrice(): float
    {
        return $this->purchasePrice;
    }

    /**
     * @param float $purchasePrice
     * @return UserSneaker
     */
    public function setPurchasePrice(float $purchasePrice): self
    {
        $this->purchasePrice = $purchasePrice;
        return $this;
    }

    /**
     * @return float
     */
    public function getShippingCost(): float
    {
        return $this->shippingCost;
    }

    /**
     * @param float $shippingCost
     * @return self
     */
    public function setShippingCost(float $shippingCost): self
    {
        $this->shippingCost = $shippingCost;
        return $this;
    }

    public function getProfit(): float
    {
        return $this->getSellingPrice() - $this->getPurchasePrice() - $this->getShippingCost();
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): UserSneaker
    {
        $this->comment = $comment;
        return $this;
    }

    public function getSize(): ShoeSize
    {
        return $this->size;
    }

    public function setSize(ShoeSize $size): UserSneaker
    {
        $this->size = $size;
        return $this;
    }
}
