<?php


namespace App\Exception;


use Throwable;

class SetNotFound extends \DomainException
{
    public function __construct(int $setId)
    {
        parent::__construct("SetPoint with id '" . $setId . "' not found");
    }
}