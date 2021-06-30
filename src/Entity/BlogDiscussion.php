<?php

namespace App\Entity;

use App\Entity\BlogBillet;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BlogDiscussionRepository;

/**
 * @ORM\Entity(repositoryClass=BlogDiscussionRepository::class)
 */
class BlogDiscussion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=BlogBillet::class, inversedBy="blogDiscussions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $billet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $auteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    public function __construct(BlogBillet $billet)
    {
        $this->date_creation = new \DateTime("now");
        $this->billet = $billet;
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getBillet(): ?BlogBillet
    {
        return $this->billet;
    }

    public function setBillet(?BlogBillet $billet): self
    {
        $this->billet = $billet;

        return $this;
    }
}
