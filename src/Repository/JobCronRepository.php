<?php

namespace Batchjobs\ManageBatchJobsBundle\Repository;


use App\Entity\Admin;
use App\Entity\JobCron;
use App\Entity\JobCronSearch;
use Cron\CronExpression;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method JobCron|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobCron|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobCron[]    findAll()
 * @method JobCron[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobCronRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobCron::class);
    }

    public function add(JobCron $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findElementById( string $id){
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(JobCron $jobCron, bool $flush = true): void
    {
        $this->_em->remove($jobCron);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function commandPossesses(string $command){
        return $this->createQueryBuilder('a')
            ->andWhere('a.scriptExec = :val')
            ->setParameter('val', $command)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllVisible():Query
    {
        return $this->findVisibleQuery()->getQuery();

    }
    public function findVisibleQuery():QueryBuilder{
        return $this->createQueryBuilder('p');


    }

    public function findSpecific(JobCronSearch $search):Query{
         $query = $this->findVisibleQuery();
         if($search->getNumero() and $search->getNumero()!=""){
                $query = $query->where('p.numero = :numero')
                    ->setParameter('numero',$search->getNumero());
         }
         if($search->getCommand() and $search->getCommand()!=""){
             $query = $query->where('p.scriptExec = :command')
                 ->setParameter('command',$search->getCommand());
         }
         if($search->getNumero()!="" and $search->getCommand()!=""){
             $query = $query->where('p.numero = :numero and p.scriptExec = :command ')

                 ->setParameter('numero',$search->getNumero())
                 ->setParameter('command',$search->getCommand());
         }
         return  $query->getQuery();
    }










    public function parse_crontab($time, $crontab) {
        // Get current minute, hour, day, month, weekday
        $time = explode(' ', date('i G j n w', strtotime($time)));
        // Split crontab by space
        $crontab = explode(' ', $crontab);
        // Foreach part of crontab
        foreach ($crontab as $k => &$v) {
            // Remove leading zeros to prevent octal comparison, but not if number is already 1 digit
            $time[$k] = preg_replace('/^0+(?=\d)/', '', $time[$k]);
            // 5,10,15 each treated as seperate parts
            $v = explode(',', $v);
            // Foreach part we now have
            foreach ($v as &$v1) {
                // Do preg_replace with regular expression to create evaluations from crontab
                $v1 = preg_replace(
                // Regex
                    array(
                        // *
                        '/^\*$/',
                        // 5
                        '/^\d+$/',
                        // 5-10
                        '/^(\d+)\-(\d+)$/',
                        // */5
                        '/^\*\/(\d+)$/'
                    ),
                    // Evaluations
                    // trim leading 0 to prevent octal comparison
                    array(
                        // * is always true
                        'true',
                        // Check if it is currently that time,
                        $time[$k] . '===\0',
                        // Find if more than or equal lowest and lower or equal than highest
                        '(\1<=' . $time[$k] . ' and ' . $time[$k] . '<=\2)',
                        // Use modulus to find if true
                        $time[$k] . '%\1===0'
                    ),
                    // Subject we are working with
                    $v1
                );
            }
            // Join 5,10,15 with `or` conditional
            $v = '(' . implode(' or ', $v) . ')';
        }
        // Require each part is true with `and` conditional
        $crontab = implode(' and ', $crontab);
        // Evaluate total condition to find if true
        return eval('return ' . $crontab . ';');
    }

    public function getNextDate(){
        $cron = new CronExpression('0 0 * * *');
        $cron->isDue();
        $cron->getNextRunDate()->format('Y-m-d H:i:s');
        return $cron;
    }

    public function giveDateTime()
    {
        return date("i G d m y");
    }


}
