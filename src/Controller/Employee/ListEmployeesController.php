<?php

namespace App\Controller\Employee;

use App\Repository\Interfaces\EmployeeRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Resource\EmployeeResource;


#[Route("/api/employees")]
class ListEmployeesController
{
    private EmployeeRepositoryInterface $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    #[Route("/list", methods: ["GET"])]
    public function __invoke(): JsonResponse
    {
        $employees = $this->employeeRepository->findAllCached();
        if (empty($employees)) {
            return new JsonResponse(["data" => []], 200);
        }


        return new JsonResponse(["data" => EmployeeResource::collection($employees)], 200);
    }
}