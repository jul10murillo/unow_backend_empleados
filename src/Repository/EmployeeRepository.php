<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Repository\Interfaces\EmployeeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Serializer\SerializerInterface;


class EmployeeRepository extends ServiceEntityRepository implements EmployeeRepositoryInterface
{
    private CacheInterface $cache;
    private SerializerInterface $serializer;

    public function __construct(ManagerRegistry $registry, SerializerInterface $serializer)
    {
        parent::__construct($registry, Employee::class);
        $this->cache = new RedisAdapter(RedisAdapter::createConnection($_ENV['REDIS_DSN']));
        $this->serializer = $serializer;
    }

    public function findAllCached(): array
    {
        $cacheKey = 'employees_list';
    
        $cachedData = $this->cache->get($cacheKey, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            // Obtener empleados de la base de datos y retornarlos correctamente
            return $this->findAll();
        });

            // Si la caché ya devuelve un array de objetos Employee, no hacer json_decode()
        if (is_array($cachedData)) {
            return $cachedData;
        }

        return [];
    }

    // Método para reconstruir un objeto Employee si la caché lo guardó como array
    private function hydrateEmployee(array $data): Employee
    {
        $employee = new Employee();
        $employee->setId($data['id']);
        $employee->setFirstName($data['firstName']);
        $employee->setLastName($data['lastName']);
        $employee->setPosition($data['position']);
        $employee->setDateOfBirth(new \DateTime($data['dateOfBirth']));

        return $employee;
    }

    public function searchByName(string $name): array
    {
        return $this->createQueryBuilder("e")
            ->where("e.firstName LIKE :name")   
            ->setParameter("name", "%$name%")
            ->getQuery()
            ->getResult();
    }

    public function findById(int $id): ?Employee
    {
        return $this->find($id);
    }

    public function save(Employee $employee): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($employee);
        $entityManager->flush();
        $this->cache->deleteItem('employees_list');
    }

    public function delete(Employee $employee): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($employee);
        $entityManager->flush();
        $this->cache->deleteItem('employees_list');
    }
}