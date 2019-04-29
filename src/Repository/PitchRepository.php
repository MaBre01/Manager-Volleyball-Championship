<?php


namespace App\Repository;


use App\Entity\Pitch;

interface PitchRepository
{
    public function getAll(): array;
    public function getById(int $pitchId): Pitch;
    public function save(Pitch $pitch): void;
    public function remove(Pitch $pitch): void;
}