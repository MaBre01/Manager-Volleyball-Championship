<?php


namespace App\Repository;


use App\Entity\Championship;

interface ChampionshipRepository
{
    public function getAll(): array;
    public function getById(int $championshipId): Championship;
    public function save(Championship $championship): void;
    public function remove(Championship $championship): void;
}