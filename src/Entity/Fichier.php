<?php

namespace App\Entity;

use App\Repository\FichierRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FichierRepository::class)]
class Fichier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fichiers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dossier $folder = null;

    #[ORM\Column(length: 255)]
    private ?string $ext = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->token = bin2hex(random_bytes(16));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFolder(): ?Dossier
    {
        return $this->folder;
    }

    public function setFolder(?Dossier $folder): static
    {
        $this->folder = $folder;

        return $this;
    }

    public function getExt(): ?string
    {
        return $this->ext;
    }

    public function setExt(?string $ext): static
    {
        $this->ext = $ext;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }
}
