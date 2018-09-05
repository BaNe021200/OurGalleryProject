<?php

namespace App\Repository;

use App\Entity\Thumbnails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Thumbnails|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thumbnails|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thumbnails[]    findAll()
 * @method Thumbnails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThumbnailsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Thumbnails::class);
    }

//    /**
//     * @return Thumbnails[] Returns an array of Thumbnails objects
//     */

    public function findByDirname($dirname)
    {
        /*return $this->createQueryBuilder('query')
            ->andWhere('query.dirname = :dir')
            ->setParameter('dir', $dirname)
            ->orderBy('query.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;*/
        $conn = $this->getEntityManager()->getConnection();
        $sql= '
            SELECT *
            FROM thumbnails query
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();





    }

    public function findByImages_id($images_id)
    {
        return $this->createQueryBuilder('query')
            ->andWhere('query.imagesId = :id')
            ->setParameter('id',$images_id)
            ->orderBy('query.imagesId','ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Thumbnails
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

   /* public function  findByDirname($dirname)
    {
         $bdd =$this->getEntityManager()->getConnection();
         $sql = '
         
         SELECT * FROM thumbnails t 
         WHERE t.dirname = "img/".$ville."/thumbs"
         
         ';
    }*/
}
