<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EmployeeRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $lastName;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $position;

    #[Assert\NotBlank]
    #[Assert\Date]
    public string $dateOfBirth;

    public function __construct(array $data)
    {
        $this->firstName = $data['firstName'] ?? '';
        $this->lastName = $data['lastName'] ?? '';
        $this->position = $data['position'] ?? '';
        $this->dateOfBirth = $data['dateOfBirth'] ?? '';
    }
}