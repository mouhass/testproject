<?php

namespace Batchjobs\ManageBatchJobsBundle\Repository;

use App\Entity\JobComposite;

use App\Entity\JobCompositeSearch;
use App\Entity\JobCron;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobComposite|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobComposite|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobComposite[]    findAll()
 * @method JobComposite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobCompositeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobComposite::class);
    }

    public function verifyJobCron(string $command,EntityManagerInterface $em){
//        return $this->createQueryBuilder('job')
//            ->andWhere('command in job.listSousJobs')
//            ->setParameter('val', $command)
//            ->getQuery()
//            ->getOneOrNullResult();

        $qb = $em->createQueryBuilder()
            ->select('j')
            ->from('JobComposite','j')
            ->andWhere(':command in j.listSousJobs')
            ->setParameter('command',$command)
            ->getQuery();
        return $qb;
    }


    public function findByCode(string $name){
        return $this->createQueryBuilder('a')
            ->andWhere('a.numerocomposite = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findVisibleQuery():QueryBuilder{
        return $this->createQueryBuilder('a');
    }
    public function findSpecific(JobCompositeSearch $search):Query
    {
        $query = $this->findVisibleQuery();
        if ($search->getNumerocomposite() and $search->getNumerocomposite() != "") {
            $query = $query->where('a.numerocomposite = :numerocomposite')
                ->setParameter('numerocomposite', $search->getNumerocomposite());
        }
//        if($search->getName() and $search->getName()!=""and $search->getNameSousJob() and $search->getNameSousJob()!="")
//        {
//            $query = $query->where('a.name = :name ')
//                ->where($query->expr()->like('a.listSousJobs',':sousJob') )
//                ->setParameter('name',$search->getName())
//                ->setParameter('sousJob',$search->getNameSousJob());
//            $query->join('a.listSousJobs','al');
//
//
//        }
        if ($search->getExpression() and $search->getExpression() != "") {
            $query = $query->where('a.expression = :expression')
                ->setParameter('expression', $search->getExpression());
        }
        if($search->getNumerocomposite()!="" and $search->getExpression()!=""){
            $query = $query->where('a.expression = :expression and a.numerocomposite = :numerocomposite')

                ->setParameter('expression', $search->getExpression())
                ->setParameter('numerocomposite', $search->getNumerocomposite());
        }
        return $query->getQuery();

    }
}
