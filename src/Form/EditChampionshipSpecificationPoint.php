<?php


namespace App\Form;


class EditChampionshipSpecificationPoint
{
    public $winPoint;
    public $winWithBonusPoint;
    public $loosePoint;
    public $looseWithBonusPoint;
    public $forfeitPoint;

    public function __construct(int $winPoint, int $winWithBonusPoint, int $loosePoint, int $looseWithBonusPoint, int $forfeitPoint)
    {
        $this->winPoint = $winPoint;
        $this->winWithBonusPoint = $winWithBonusPoint;
        $this->loosePoint = $loosePoint;
        $this->looseWithBonusPoint = $looseWithBonusPoint;
        $this->forfeitPoint = $forfeitPoint;
    }


}