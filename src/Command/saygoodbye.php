<?php

// src/Command/CreateUserCommand.php
namespace Batchjobs\ManageBatchJobsBundle\Command;

use App\Controller\MailerController;
use App\Entity\Historique;
use App\Repository\JobCompositeRepository;
use App\Repository\JobCronRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;

class saygoodbye extends Command
{
    protected static $defaultName = 'app:saygoodbye';
    private $mailer;
    private $logger;
    private $kernel;
    private $manager;
    private $repository;
    private $managerRegistry;
    public function __construct(string $name = null,MailerInterface $mailer,LoggerInterface $logger,KernelInterface $kernel,EntityManagerInterface $manager, JobCronRepository $repository,ManagerRegistry $managerRegistry)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->manager = $manager;
        $this->repository = $repository;
        $this->managerRegistry = $managerRegistry;
        parent::__construct($name);
    }


    protected function configure(): void
    {
        $this
            ->addArgument('Related_job', InputArgument::OPTIONAL, 'Whitch one this command is related to?')
            ->addArgument('nom_job_composite',InputArgument::OPTIONAL, 'si la commande est lancée à partir de job composite?')
            ->addArgument('dernier_Sous_Job',InputArgument::OPTIONAL,'si c est loe dernier sous job ?')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $jobCron = $this->repository->findElementById($input->getArgument('Related_job'));
            sleep(40);
            //$output->writeln("goodbye goodbye");
//            //$myfile = fopen("webdictionary.txt", "r");
            $output->writeln("goodbye goodbye");
            $log = "command name: app:saygoodbye  state: success  execution date" . ' - ' . date("F j, Y, G:i") . PHP_EOL .
                "-------------------------" . PHP_EOL;
            if($input->getArgument('nom_job_composite')=="0") {
                file_put_contents($this->kernel->getProjectDir() . '/var/log/saygoodbye_succes' . date("y-m-d-G-i-s") . '.log', $log, FILE_APPEND);
            }
            else{
                file_put_contents($this->kernel->getProjectDir() . '/var/log/saygoodbye_succes'.$input->getArgument('nom_job_composite') . date("y-m-d-G-i-s") . '.log', $log, FILE_APPEND);

            }
            $historique = new Historique();
            $historique->setCreatedAt(new \DateTime());
            if($input->getArgument('nom_job_composite')=="0") {
                $historique->setPath('/var/log/saygoodbye_succes' . date("y-m-d-G-i-s") . '.log');
            }
            else{
                $historique->setPath('/var/log/saygoodbye_succes' . $input->getArgument('nom_job_composite') . date("y-m-d-G-i-s") .'--'.$input->getArgument('dernier_Sous_Job'). '.log');

            }
            $historique->setJobCronHist($jobCron);
            $this->manager->persist($historique);
            $this->manager->flush();
            $jobCron->setState("Succès");
            $this->manager->persist($jobCron);
            $this->manager->flush();

            if($input->getArgument('dernier_Sous_Job')=="1"){
                $jobCompositeRepo = new JobCompositeRepository($this->managerRegistry);
                $jobComposite = $jobCompositeRepo->findByName($input->getArgument('nom_job_composite'));
                if($jobComposite->getState()!="Erreur"){
                $jobComposite->setState("Succès");
                $this->manager->persist($jobComposite);
                $this->manager->flush();}
            }
        }
        catch(\Exception $exception){
            $jobCron = $this->repository->findElementById($input->getArgument('Related_job'));
            $jobCron->setState("erreur");
            $this->manager->persist($jobCron);
            $this->manager->flush();
            $log = "command name: app:saygoodbye  state: error  error date" . ' - ' . date("F j, Y, G:i") . PHP_EOL .
                "error description : ".$exception.
                "-------------------------" . PHP_EOL;
            file_put_contents($this->kernel->getProjectDir() . '/var/log/saygoodbye_error' . date("y-m-d-G-i-s") . '.log', $log, FILE_APPEND);

            $email = new MailerController();
            $email->sendEmail($this->mailer, "Un erreur dans l'exécution du job dont la commande est app:saygoodbye");
            $historique = new Historique();
            $historique->setCreatedAt(new \DateTime());
            $historique->setPath('/var/log/saygoodbye_erreur' . date("y-m-d-G-i-s") . '.log');
            $jobCron = $this->repository->findElementById($input->getArgument('Related_job'));
            $historique->setJobCronHist($jobCron);
            $this->manager->persist($historique);
            $this->manager->flush();
            if($input->getArgument('nom_job_composite')!="0") {
                $jobCompositeRepo = new JobCompositeRepository($this->managerRegistry);
                $jobComposite = $jobCompositeRepo->findByName($input->getArgument('nom_job_composite'));
                $jobComposite->setState("Erreur");
                $this->manager->persist($jobComposite);
                $this->manager->flush();
            }

        }


        // $this->logger->info("Greeted: succes");
        //            $email = new MailerController();
        //            $email->sendEmail($this->mailer, "can't say hello world properly");
        //



        return(1);
    }
}
