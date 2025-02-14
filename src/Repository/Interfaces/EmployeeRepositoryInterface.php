<?php

namespace App\Repository\Interfaces;

use App\Entity\Employee;

interface EmployeeRepositoryInterface
{
    public function findAllCached(): array;

    public function searchByName(string $name): array;

    public function findById(int $id): ?Employee;

    public function save(Employee $employee): void;

    public function delete(Employee $employee): void;
}