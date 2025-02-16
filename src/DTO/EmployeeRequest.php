<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EmployeeRequest
{
    #[Assert\NotBlank(groups: ["create"])]
    #[Assert\Length(min: 2, max: 255)]
    public string $firstName;

    #[Assert\NotBlank(groups: ["create"])]
    #[Assert\Length(min: 2, max: 255)]
    public string $lastName;

    #[Assert\NotBlank(groups: ["create"])]
    #[Assert\Length(min: 2, max: 255)]
    public string $position;

    #[Assert\NotBlank(groups: ["create"])]
    #[Assert\Date]
    public string $dateOfBirth;

    #[Assert\NotBlank(groups: ["create"])]
    #[Assert\Email]
    public string $email;

    public function __construct(array $data)
    {
        $this->firstName = $data['firstName'] ?? '';
        $this->lastName = $data['lastName'] ?? '';
        $this->position = $data['position'] ?? '';
        $this->dateOfBirth = $data['dateOfBirth'] ?? '';
        $this->email = $data['email'] ?? '';
    }
}