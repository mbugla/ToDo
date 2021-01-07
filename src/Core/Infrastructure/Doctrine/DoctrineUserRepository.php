<?php

namespace App\Core\Infrastructure\Doctrine;

use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\User\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

class DoctrineUserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, string $entityClass = User::class)
    {
        parent::__construct($registry, $entityClass);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->findOneBy(['username'=>$username]);
    }

    public function add(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function remove(User $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }

    public function findByUuid(UuidInterface $id): ?User
    {
        return $this->find($id->toString());
    }
}
