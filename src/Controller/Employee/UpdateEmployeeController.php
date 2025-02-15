<?php

namespace App\Controller\Employee;

use App\Repository\Interfaces\EmployeeRepositoryInterface;
use App\DTO\EmployeeRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route("/api/employees/update")]
class UpdateEmployeeController
{
    private EmployeeRepositoryInterface $employeeRepository;
    private ValidatorInterface $validator;

    public function __construct(EmployeeRepositoryInterface $employeeRepository, ValidatorInterface $validator)
    {
        $this->employeeRepository = $employeeRepository;
        $this->validator = $validator;
    }

    #[Route("/{id}", methods: ["PUT"])]
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $employee = $this->employeeRepository->findById($id);

        if (!$employee) {
            return new JsonResponse(["error" => "Employee not found"], 404);
        }

        $data = json_decode($request->getContent(), true);
        $employeeRequest = new EmployeeRequest($data);

        $errors = $this->validator->validate($employeeRequest);
        if (count($errors) > 0) {
            return new JsonResponse(["error" => (string) $errors], 400);
        }

        $employee->setFirstName($employeeRequest->firstName);
        $employee->setLastName($employeeRequest->lastName);
        $employee->setPosition($employeeRequest->position);
        $employee->setDateOfBirth(new \DateTime($employeeRequest->dateOfBirth));

        $this->employeeRepository->save($employee);

        return new JsonResponse(["message" => "Employee updated"]);
    }
}