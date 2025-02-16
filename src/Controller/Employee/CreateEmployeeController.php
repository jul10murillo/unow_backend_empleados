<?php

namespace App\Controller\Employee;

use App\Entity\Employee;
use App\DTO\EmployeeRequest;
use App\Repository\Interfaces\EmployeeRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route("/api/employees")]
class CreateEmployeeController
{
    private EmployeeRepositoryInterface $employeeRepository;
    private ValidatorInterface $validator;

    public function __construct(EmployeeRepositoryInterface $employeeRepository, ValidatorInterface $validator)
    {
        $this->employeeRepository = $employeeRepository;
        $this->validator = $validator;
    }

    #[Route("/create", methods: ["POST"])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $employeeRequest = new EmployeeRequest($data);

        $errors = $this->validator->validate($employeeRequest);
        if (count($errors) > 0) {
            return new JsonResponse(["error" => (string) $errors], 400);
        }

        $employee = new Employee();
        $employee->setFirstName($employeeRequest->firstName);
        $employee->setLastName($employeeRequest->lastName);
        $employee->setPosition($employeeRequest->position);
        $employee->setDateOfBirth(new \DateTime($employeeRequest->dateOfBirth));

        $this->employeeRepository->save($employee);

        return new JsonResponse(["message" => "Employee created"], 201);
    }
}