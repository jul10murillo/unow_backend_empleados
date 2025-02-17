<?php

namespace App\Controller\Employee;

use App\Entity\Employee;
use App\DTO\EmployeeRequest;
use App\Repository\Interfaces\EmployeeRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MailService;

#[Route("/api/employees")]
class CreateEmployeeController
{
    private EmployeeRepositoryInterface $employeeRepository;
    private ValidatorInterface $validator;

    public function __construct(EmployeeRepositoryInterface $employeeRepository, ValidatorInterface $validator, MailService $mailService)
    {
        $this->employeeRepository = $employeeRepository;
        $this->validator = $validator;
        $this->mailService = $mailService;
    }

    #[Route("/create", methods: ["POST"])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $employeeRequest = new EmployeeRequest($data);

        // Validación de datos de entrada
        $errors = $this->validator->validate($employeeRequest);
        if (count($errors) > 0) {
            return new JsonResponse(["error" => (string) $errors], 400);
        }

        try {
            $employee = new Employee();
            $employee->setFirstName($employeeRequest->firstName);
            $employee->setLastName($employeeRequest->lastName);
            $employee->setPosition($employeeRequest->position);
            $employee->setDateOfBirth(new \DateTime($employeeRequest->dateOfBirth));
            $employee->setEmail($employeeRequest->email);

            $this->employeeRepository->save($employee);

            // Enviar correo de bienvenida
            $this->mailService->sendWelcomeEmail($employeeRequest->email, $employeeRequest->firstName . " " . $employeeRequest->lastName);

            return new JsonResponse(["message" => "Employee created"], 201);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(["error" => "The email is already registered."], 400);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An unexpected error occurred."], 500);
        }
    }
}