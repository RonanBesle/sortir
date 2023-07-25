<?php

namespace App\Repository;

use App\DTO\SortieIndexFiltreDTO;
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


    /**
     * Récupère les sorties de la page sortie/index
     * @return Sortie[]
     */
    public function findSearch(SortieIndexFiltreDTO $search, User $user): array
    {
        $user=$this->getUser();
        $user->getId();

        $query = $this->createQueryBuilder('s');

        if (!empty($search->campusRecherche)) {
            $query = $query
                ->andWhere('s.campus = :campus')
                ->setParameter('campus', $search->campusRecherche);
        }

        if (!empty($search->nomRecherche)) {
            $query = $query
                ->andWhere('s.nom LIKE :nomRecherche')
                ->setParameter('nomRecherche', "%{$search->nomRecherche}%");
        }

        if (!empty($search->organisateurBoolean)) {
            $query = $query
                ->andWhere('s.organisateur = :user')
                ->setParameter('user', $user)
                ->setParameter('organisateurBoolean', true);
        }



        return $query->getQuery()->getResult();
    }
}
