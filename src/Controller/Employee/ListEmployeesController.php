<?php

namespace App\Controller\Employee;

use App\Repository\Interfaces\EmployeeRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/employees/list")]
class ListEmployeesController
{
    private EmployeeRepositoryInterface $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    #[Route("/", methods: ["GET"])]
    public function __invoke(): JsonResponse
    {
        $employees = $this->employeeRepository->findAllCached();
        return new JsonResponse($employees);
    }
}