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

            return $this->createQueryBuilder('e')
                ->where('e.deletedAt IS NULL') // Filtrar solo los empleados activos
                ->getQuery()
                ->getResult();;
        });

            // Si la caché ya devuelve un array de objetos Employee, no hacer json_decode()
        if (is_array($cachedData)) {
            return $cachedData;
        }

        return [];
    }

    public function findById(int $id): ?Employee
    {
        return $this->createQueryBuilder('e')
            ->where('e.id = :id')
            ->andWhere('e.deletedAt IS NULL') // Solo empleados activos
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(); 
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
        $employee->softDelete();
        $entityManager->persist($employee);
        $entityManager->flush();
        $this->cache->deleteItem('employees_list');
    }
}