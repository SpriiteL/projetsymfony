<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\NotesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
#[ApiResource]
class Notes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_notes = null;

    #[ORM\ManyToOne(inversedBy: 'note')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    private ?Product $product = null;

    public function __toString(): String{
        $nameParts = [];
        if($this->note){
            $nameParts[] = $this->note;
        }
        return implode(' ', $nameParts);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getDateNotes(): ?\DateTimeInterface
    {
        return $this->date_notes;
    }

    public function setDateNotes(\DateTimeInterface $date_notes): static
    {
        $this->date_notes = $date_notes;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
