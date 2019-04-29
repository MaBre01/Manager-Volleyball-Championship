<?php


namespace App\Exception;


use Throwable;

class PitchNotFound extends \DomainException
{
    public function __construct(int $pitchId)
    {
        parent::__construct("Pitch with id '" . $pitchId . "' not found");
    }
}