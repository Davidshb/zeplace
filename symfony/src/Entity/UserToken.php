<?php

namespace App\Entity;

use App\Repository\UserTokenRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserTokenRepository::class)]
class UserToken
{

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tokens')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: Types::TEXT)]
    private string $token;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private DateTime $expireAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserToken
     */
    public function setUser(User $user): UserToken
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return UserToken
     */
    public function setToken(string $token): UserToken
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpireAt(): DateTime
    {
        return $this->expireAt;
    }

    /**
     * @param DateTime $expireAt
     * @return UserToken
     */
    public function setExpireAt(DateTime $expireAt): UserToken
    {
        $this->expireAt = $expireAt;
        return $this;
    }
}