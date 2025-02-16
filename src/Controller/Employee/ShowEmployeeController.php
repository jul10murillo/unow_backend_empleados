<?php

namespace App\Controller\Employee;

use App\Repository\Interfaces\EmployeeRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Resource\EmployeeResource;


#[Route("/api/employees")]
class ShowEmployeeController
{
    private EmployeeRepositoryInterface $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    #[Route("/show/{id}", methods: ["GET"])]
    public function __invoke(int $id, SerializerInterface $serializer): JsonResponse
    {
        $employee = $this->employeeRepository->findById($id);
        if (!$employee) {
            return new JsonResponse(["error" => "Employee not found"], 404);
        }

        return new JsonResponse(["data" => EmployeeResource::toArray($employee)], 200);
    }
}