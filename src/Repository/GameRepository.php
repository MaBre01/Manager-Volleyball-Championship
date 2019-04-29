<?php


namespace App\Repository;


use App\Entity\Game;

interface GameRepository
{
    public function getAll(): array;
    public function getById(int $gameId): Game;
    public function save(Game $game): void;
    public function remove(Game $game): void;
}