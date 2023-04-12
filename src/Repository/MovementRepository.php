<?php
namespace App\Repository;
use App\Entity\Movement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @extends ServiceEntityRepository<Movement>
 *
 * @method Movement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movement[]    findAll()
 * @method Movement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movement::class);
    }
    public function getAllMovements($game_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * 
            FROM movement 
            WHERE game_id=:game_id ORDER BY id DESC
            LIMIT 1
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['game_id' => $game_id]);
        $resultSet=$resultSet->fetchAllAssociative();
        if(isset($resultSet[0]["game_id"]) && strlen($resultSet[0]["game_id"])){
            return $resultSet;
        }else{
            return 0;
        }
    }
    public function save(Movement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Movement[] Returns an array of Movement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movement
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
