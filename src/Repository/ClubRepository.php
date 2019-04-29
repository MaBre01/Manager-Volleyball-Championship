<?php


namespace App\Repository;


use App\Entity\Club;

interface ClubRepository
{
    public function getAll(): array;
    public function getById(int $clubId): Club;
    public function save(Club $club): void;
    public function remove(Club $club): void;
}