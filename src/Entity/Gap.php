<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctrineGapRepository")
 */
class Gap
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $day;

    public function __construct(string $day)
    {
        $this->day = $day;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): string
    {
        return $this->day;
    }
}
