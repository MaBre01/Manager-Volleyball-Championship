<?php

namespace App\Entity;

use App\Form\EditChampionshipSpecificationPoint;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class SpecificationPoint
{
    /**
     * @ORM\Column(type="integer")
     */
    private $winPoint;

    /**
     * @ORM\Column(type="integer")
     */
    private $winWithBonusPoint;

    /**
     * @ORM\Column(type="integer")
     */
    private $loosePoint;

    /**
     * @ORM\Column(type="integer")
     */
    private $looseWithBonusPoint;

    /**
     * @ORM\Column(type="integer")
     */
    private $forfeitPoint;


    public function __construct(int $winPoint, int $winWithBonusPoint, int $loosePoint, int $looseWithBonusPoint, int $forfeitPoint)
    {
        $this->winPoint = $winPoint;
        $this->winWithBonusPoint = $winWithBonusPoint;
        $this->loosePoint = $loosePoint;
        $this->looseWithBonusPoint = $looseWithBonusPoint;
        $this->forfeitPoint = $forfeitPoint;
    }

    public static function create(EditChampionshipSpecificationPoint $editChampionshipSpecificationPoint): self
    {
        return new self($editChampionshipSpecificationPoint->winPoint, $editChampionshipSpecificationPoint->winWithBonusPoint, $editChampionshipSpecificationPoint->loosePoint, $editChampionshipSpecificationPoint->looseWithBonusPoint, $editChampionshipSpecificationPoint->forfeitPoint);
    }

    public function getWinPoint(): ?int
    {
        return $this->winPoint;
    }

    public function getWinWithBonusPoint(): ?int
    {
        return $this->winWithBonusPoint;
    }

    public function getLoosePoint(): ?int
    {
        return $this->loosePoint;
    }

    public function getLooseWithBonusPoint(): ?int
    {
        return $this->looseWithBonusPoint;
    }

    public function getForfeitPoint(): ?int
    {
        return $this->forfeitPoint;
    }
}
