<?php


namespace App\Repository;


use App\Entity\Championship;
use App\Entity\GameDay;

interface GameDayRepository
{
    public function getAll(): array;
    public function getById(int $gameDayId): GameDay;
    public function getGameDayByNumber(Championship $championship, int $number): GameDay;
    public function save(GameDay $gameDay): void;
    public function remove(GameDay $gameDay): void;
}