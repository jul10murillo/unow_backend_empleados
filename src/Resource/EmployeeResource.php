<?php
namespace App\Resource;

use App\Entity\Employee;

class EmployeeResource
{
    public static function toArray(Employee $employee): array
    {
        return [
            'id' => $employee->getId(),
            'firstName' => $employee->getFirstName(),
            'lastName' => $employee->getLastName(),
            'position' => $employee->getPosition(),
            'dateOfBirth' => $employee->getDateOfBirth()->format('Y-m-d'),
        ];
    }

    public static function collection(array $employees): array
    {
        return array_map(fn(Employee $employee) => self::toArray($employee), $employees);
    }
}