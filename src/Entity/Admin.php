<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin extends User
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
    private $matricle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricle(): ?string
    {
        return $this->matricle;
    }

    public function setMatricle(string $matricle): self
    {
        $this->matricle = $matricle;

        return $this;
    }
}
