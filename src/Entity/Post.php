<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?User $author = null;

    #[ORM\Column]
    private ?int $ArtistId = null;

    #[ORM\Column(length: 255)]
    private ?string $ArtistName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ArtistPicture = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ArtistDeezerPictureSmall = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ArtistDeezerPictureMedium = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ArtistDeezerPictureBig = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ArtistDeezerPictureXl = null;

    #[ORM\Column]
    private ?int $ArtistDeezerNbAlbums = null;

    #[ORM\Column]
    private ?int $ArtistDeezerNbFans = null;

    #[ORM\Column]
    private ?bool $ArtistDeezerRadio = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ArtistDeezerTracklist = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ArtistLink = null;

    #[ORM\Column(length: 255)]
    private ?string $ArtistDeezerType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getArtistId(): ?int
    {
        return $this->ArtistId;
    }

    public function setArtistId(int $ArtistId): static
    {
        $this->ArtistId = $ArtistId;

        return $this;
    }

    public function getArtistName(): ?string
    {
        return $this->ArtistName;
    }

    public function setArtistName(string $ArtistName): static
    {
        $this->ArtistName = $ArtistName;

        return $this;
    }

    public function getArtistPicture(): ?string
    {
        return $this->ArtistPicture;
    }

    public function setArtistPicture(string $ArtistPicture): static
    {
        $this->ArtistPicture = $ArtistPicture;

        return $this;
    }

    public function getArtistDeezerPictureSmall(): ?string
    {
        return $this->ArtistDeezerPictureSmall;
    }

    public function setArtistDeezerPictureSmall(string $ArtistDeezerPictureSmall): static
    {
        $this->ArtistDeezerPictureSmall = $ArtistDeezerPictureSmall;

        return $this;
    }

    public function getArtistDeezerPictureMedium(): ?string
    {
        return $this->ArtistDeezerPictureMedium;
    }

    public function setArtistDeezerPictureMedium(string $ArtistDeezerPictureMedium): static
    {
        $this->ArtistDeezerPictureMedium = $ArtistDeezerPictureMedium;

        return $this;
    }

    public function getArtistDeezerPictureBig(): ?string
    {
        return $this->ArtistDeezerPictureBig;
    }

    public function setArtistDeezerPictureBig(string $ArtistDeezerPictureBig): static
    {
        $this->ArtistDeezerPictureBig = $ArtistDeezerPictureBig;

        return $this;
    }

    public function getArtistDeezerPictureXl(): ?string
    {
        return $this->ArtistDeezerPictureXl;
    }

    public function setArtistDeezerPictureXl(string $ArtistDeezerPictureXl): static
    {
        $this->ArtistDeezerPictureXl = $ArtistDeezerPictureXl;

        return $this;
    }

    public function getArtistDeezerNbAlbums(): ?int
    {
        return $this->ArtistDeezerNbAlbums;
    }

    public function setArtistDeezerNbAlbums(int $ArtistDeezerNbAlbums): static
    {
        $this->ArtistDeezerNbAlbums = $ArtistDeezerNbAlbums;

        return $this;
    }

    public function getArtistDeezerNbFans(): ?int
    {
        return $this->ArtistDeezerNbFans;
    }

    public function setArtistDeezerNbFans(int $ArtistDeezerNbFans): static
    {
        $this->ArtistDeezerNbFans = $ArtistDeezerNbFans;

        return $this;
    }

    public function isArtistDeezerRadio(): ?bool
    {
        return $this->ArtistDeezerRadio;
    }

    public function setArtistDeezerRadio(bool $ArtistDeezerRadio): static
    {
        $this->ArtistDeezerRadio = $ArtistDeezerRadio;

        return $this;
    }

    public function getArtistDeezerTracklist(): ?string
    {
        return $this->ArtistDeezerTracklist;
    }

    public function setArtistDeezerTracklist(string $ArtistDeezerTracklist): static
    {
        $this->ArtistDeezerTracklist = $ArtistDeezerTracklist;

        return $this;
    }

    public function getArtistLink(): ?string
    {
        return $this->ArtistLink;
    }

    public function setArtistLink(string $ArtistLink): static
    {
        $this->ArtistLink = $ArtistLink;

        return $this;
    }

    public function getArtistDeezerType(): ?string
    {
        return $this->ArtistDeezerType;
    }

    public function setArtistDeezerType(string $ArtistDeezerType): static
    {
        $this->ArtistDeezerType = $ArtistDeezerType;

        return $this;
    }
}
