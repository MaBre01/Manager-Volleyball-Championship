<?php


namespace App\Repository;


use App\Entity\Championship;
use App\Entity\Team;

interface TeamRepository
{
    public function getAll(): array;
    public function getById(int $teamId): Team;
    public function getAllWithoutChampionship(): array;
    public function getAllByChampionship(Championship $championship): array;
    public function save(Team $team): void;
    public function remove(Team $team): void;
}