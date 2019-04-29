<?php


namespace App\Repository;


use App\Entity\Set;

interface SetRepository
{
    public function getAll(): array;
    public function getById(int $setId): Set;
    public function save(Set $set): void;
    public function remove(Set $set): void;
}