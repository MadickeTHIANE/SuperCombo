<?php

namespace App\Entity;

use App\Entity\Image;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SlideRepository;

/**
 * @ORM\Entity(repositoryClass=SlideRepository::class)
 */
class Slide
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $extrait;

    /**
     * @ORM\Column(type="boolean")
     */
    private $textDark;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bgDark;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="text")
     */
    private $lien;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, mappedBy="slide")
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $button;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getExtrait(): ?string
    {
        return $this->extrait;
    }

    public function setExtrait(?string $extrait): self
    {
        $this->extrait = $extrait;

        return $this;
    }

    public function getTextDark(): ?bool
    {
        return $this->textDark;
    }

    public function setTextDark(bool $textDark): self
    {
        $this->textDark = $textDark;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }



    public function getButton(): ?string
    {
        return $this->button;
    }

    public function setButton(string $button): self
    {
        $this->button = $button;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getBgDark(): ?bool
    {
        return $this->bgDark;
    }

    public function setBgDark(bool $bgDark): self
    {
        $this->bgDark = $bgDark;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
