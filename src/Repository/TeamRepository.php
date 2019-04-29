<?php


namespace App\Repository;


use App\Entity\Team;

interface TeamRepository
{
    public function getAll(): array;
    public function getById(int $teamId): Team;
    public function save(Team $team): void;
    public function remove(Team $team): void;
}