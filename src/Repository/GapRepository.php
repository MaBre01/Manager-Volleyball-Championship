<?php


namespace App\Repository;


use App\Entity\Gap;

interface GapRepository
{
    public function getAll(): array;
    public function getById(int $gapId): Gap;
    public function save(Gap $gap): void;
    public function remove(Gap $gap): void;
}