<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Trick $trick = null;

    private ?UploadedFile $imageFile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ?string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param ?string $path
     * @return $this
     */
    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return ?\DateTimeImmutable
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return ?Trick
     */
    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    /**
     * @param ?Trick $trick
     * @return $this
     */
    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    /**
     * @return ?UploadedFile
     */
    public function getImageFile(): ?UploadedFile
    {
        return $this->imageFile;
    }

    /**
     * @param ?UploadedFile $imageFile
     * @return void
     */
    public function setImageFile(?UploadedFile $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }
}