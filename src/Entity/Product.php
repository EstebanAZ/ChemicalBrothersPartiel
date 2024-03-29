<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Fds::class, orphanRemoval: true)]
    private Collection $fds;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'products')]
    private Collection $users;

    public function __construct()
    {
        $this->fds = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Fds>
     */
    public function getFds(): Collection
    {
        return $this->fds;
    }

    public function addFd(Fds $fd): static
    {
        if (!$this->fds->contains($fd)) {
            $this->fds->add($fd);
            $fd->setProduct($this);
        }

        return $this;
    }

    public function removeFd(Fds $fd): static
    {
        if ($this->fds->removeElement($fd)) {
            // set the owning side to null (unless already changed)
            if ($fd->getProduct() === $this) {
                $fd->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addProduct($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeProduct($this);
        }

        return $this;
    }
}
