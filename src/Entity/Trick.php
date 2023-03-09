<?php

namespace App\Entity;

use Collection;
use App\Entity\User;
use App\Entity\Video;
use App\Entity\Picture;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TrickRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_update = null;

    #[ORM\ManyToOne(targetEntity:"App\Entity\User", inversedBy:"tricks")]
    private $users;

    #[ORM\OneToMany(targetEntity:"App\Entity\Video", mappedBy:"tricks", orphanRemoval:true, cascade: ['persist'])]
    private $videos;

    #[ORM\OneToMany(targetEntity:"App\Entity\Picture", mappedBy:"tricks", orphanRemoval:true, cascade: ['persist'])]
    private $pictures;

    #[ORM\OneToMany(targetEntity:"App\Entity\Comment", mappedBy:"tricks", orphanRemoval:true)]
    private $comments;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\File(mimeTypes: ['image/jpeg', 'image/png', 'image/tiff', 'image/svg+xml'])]
    private $mainImage;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

        return $this;
    }

    /**
     * Get the value of videos
     */ 
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Set the value of videos
     *
     * @return  self
     */ 
    public function setVideos($videos)
    {
        $this->videos = $videos;

        return $this;
    }

    /**
     * Get the value of users
     */ 
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set the value of users
     *
     * @return  self
     */ 
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get the value of comments
     */ 
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set the value of comments
     *
     * @return  self
     */ 
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get the value of pictures
     */ 
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Set the value of pictures
     *
     * @return  self
     */ 
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    /**
     * Get the value of mainImage
     */ 
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * Set the value of mainImage
     *
     * @return  self
     */ 
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;

        return $this;
    }
}
