<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Repository\Interfaces\EmployeeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class EmployeeRepository extends ServiceEntityRepository implements EmployeeRepositoryInterface
{
    private $cache;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
        $this->cache = new RedisAdapter(
            RedisAdapter::createConnection($_ENV['REDIS_DSN'])
        );
    }

    public function findAllCached(): array
    {
        $cacheKey = 'employees_list';
        $cachedItem = $this->cache->getItem($cacheKey);

        if (!$cachedItem->isHit()) {
            $employees = $this->findAll();
            $cachedItem->set($employees);
            $cachedItem->expiresAfter(3600);
            $this->cache->save($cachedItem);
        }

        return $cachedItem->get();
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
        $this->_em->persist($employee);
        $this->_em->flush();
    }

    public function delete(Employee $employee): void
    {
        $this->_em->remove($employee);
        $this->_em->flush();
    }
}