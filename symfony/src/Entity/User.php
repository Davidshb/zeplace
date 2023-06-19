<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: "Un compte avec ce nom d'utilisateur existe déjà")]
#[UniqueEntity(fields: ['email'], message: "Un compte avec cet email existe déjà")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private string $username;

    #[ORM\Column(unique: true)]
    private string $email;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: Types::TEXT)]
    private string $password;

    /** @var Collection<int, UserSneaker> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserSneaker::class, orphanRemoval: true)]
    private Collection $userSneakers;

    public function __construct()
    {
        $this->userSneakers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        $key = array_search($role, $this->roles);

        if ($key !== false) {
            unset($this->roles[$key]);
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
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
            $userSneaker->setUser($this);
        }

        return $this;
    }

    public function removeUserSneaker(UserSneaker $userSneaker): self
    {
        if ($this->userSneakers->removeElement($userSneaker)) {
            // set the owning side to null (unless already changed)
            if ($userSneaker->getUser() === $this) {
                $userSneaker->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }
}
