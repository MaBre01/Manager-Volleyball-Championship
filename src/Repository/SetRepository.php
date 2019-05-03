<?php


namespace App\Repository;


use App\Entity\SetPoint;

interface SetRepository
{
    public function getAll(): array;
    public function getById(int $setId): SetPoint;
    public function save(SetPoint $set): void;
    public function remove(SetPoint $set): void;
}