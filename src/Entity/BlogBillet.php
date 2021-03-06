<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\BlogDiscussion;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BlogBilletRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=BlogBilletRepository::class)
 */
class BlogBillet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $auteur;

    /**
     *@ORM\ManyToOne(targetEntity=User::class, inversedBy="billets") 
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\OneToMany(targetEntity=BlogDiscussion::class, mappedBy="billet")
     * @ORM\JoinColumn(nullable=true)
     */
    private $blogDiscussions;

    public function __construct()
    {
        $this->date_creation = new \DateTime("now");
        $this->blogDiscussions = new ArrayCollection();
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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    /**
     * @return Collection|BlogDiscussion[]
     */
    public function getBlogDiscussions(): Collection
    {
        return $this->blogDiscussions;
    }

    public function addBlogDiscussion(BlogDiscussion $blogDiscussion): self
    {
        if (!$this->blogDiscussions->contains($blogDiscussion)) {
            $this->blogDiscussions[] = $blogDiscussion;
            $blogDiscussion->setBillet($this);
        }

        return $this;
    }

    public function removeBlogDiscussion(BlogDiscussion $blogDiscussion): self
    {
        if ($this->blogDiscussions->removeElement($blogDiscussion)) {
            // set the owning side to null (unless already changed)
            if ($blogDiscussion->getBillet() === $this) {
                $blogDiscussion->setBillet(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
