<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findByDateTimeField(\DateTime $value): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.createdOn > :value')
            ->setParameter('value', $value)
            ->orderBy('p.createdOn', 'DESC');


        $query = $qb->getQuery();

        return $query->execute();
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findByOwnerId(\DateTime $currentDate, int $ownerId): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.createdOn > :value')
            ->andwhere('p.owner = :owner')
            ->setParameters(array('value' => $currentDate, 'owner' => $ownerId))
            ->orderBy('p.createdOn', 'DESC');


        $query = $qb->getQuery();

        return $query->execute();
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findByDateAndSearch(\DateTime $value, string $search = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->andwhere('p.createdOn > :value')
            ->setParameter('value', $value)
            ->orderBy('p.createdOn', 'DESC');
        if ($search) {
            $qb->andWhere('lower(p.title) LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.strtolower($search).'%');
        }
        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findByOwnerDateSearch(\DateTime $currentDate,  int $ownerId, string $search = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.createdOn > :value')
            ->setParameter('value', $currentDate)
            ->orderBy('p.createdOn', 'DESC');
        if($ownerId){
            $qb->andwhere('p.owner = :owner')
               ->setParameter('owner', $ownerId);
        }
        if ($search) {
            $qb->andWhere('lower(p.title) LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.strtolower($search).'%');
        }


        $query = $qb->getQuery();

        return $query->execute();
    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
