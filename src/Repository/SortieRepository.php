<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\User;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;


/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    public function add(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCampus($campus)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.campus = :campus')
            ->setParameter('campus', $campus)
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(?Campus $campus, ?string $nomSortie, ?DateTimeInterface $dateDebut, ?User $organisateur)
    {
        $query = $this->createQueryBuilder('s');

        // Appliquer les filtres en fonction des valeurs fournies
        if ($campus) {
            $query->andWhere('s.campus = :campus')
                ->setParameter('campus', $campus);
        }

        if ($nomSortie) {
            $query->andWhere('s.nom LIKE :nomSortie')
                ->setParameter('nomSortie', '%' . $nomSortie . '%');
        }
        if ($dateDebut) {
            $query->andWhere('s.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $dateDebut);
        }

        if ($organisateur) {
            $query->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur', $organisateur);
        }

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
