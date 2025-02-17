<?php

namespace App\Controller\Employee;

use App\Repository\Interfaces\EmployeeRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/employees/delete")]
class DeleteEmployeeController
{
    private EmployeeRepositoryInterface $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    #[Route("/{id}", methods: ["DELETE"])]
    public function __invoke(int $id): JsonResponse
    {
        $employee = $this->employeeRepository->findById($id);

        if (!$employee) {
            return new JsonResponse(["error" => "Employee not found"], 404);
        }

        $this->employeeRepository->delete($employee);

        return new JsonResponse(["message" => "Employee deleted successfully"]);
    }
}