<?php


namespace App\Exception;


use Throwable;

class GapNotFound extends \DomainException
{
    public function __construct(int $gapId)
    {
        parent::__construct("Gap with id '" . $gapId . "' not found");
    }
}