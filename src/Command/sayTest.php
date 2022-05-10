<?php

namespace Batchjobs\ManageBatchJobsBundle\Command;

use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;

class sayTest extends Command
{
    protected static $defaultName = 'app:saytest';
    private $mailer;
    private $logger;
    private $kernel;
    private $manager;

    public function __construct(string $name = null,MailerInterface $mailer,LoggerInterface $logger,KernelInterface  $kernel,EntityManagerInterface $manager)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->manager = $manager;
        parent::__construct($name);
    }


    protected function configure(): void
    {

    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            sleep(50);
            // $output->writeln("wow wow");
            //$myfile = fopen("webdictionary.txt", "r");
            $output->writeln("test test");
            $log = "command name: app:saytest  state: success  execution date" . ' - ' . date("F j, Y, G:i") . PHP_EOL .
                "-------------------------" . PHP_EOL;
            file_put_contents($this->kernel->getProjectDir().'/var/log/saytest_succes' . date("y-m-d-G-i-s") . '.log', $log, FILE_APPEND);

//            $hitorique = new Historique();
//            $hitorique->setCreatedAt(new \DateTime());
//            $hitorique->setPath('/var/log/saywow_succes' . date("y-m-d-G-i-s") . '.log');
//
//           $this->manager->persist($hitorique);
//            $this->manager->flush();
        }
        catch(\Exception $exception){
            $log = "command name: app:saytest  state: error  error date" . ' - ' . date("F j, Y, G:i") . PHP_EOL .
                "error description : ".$exception.
                "-------------------------" . PHP_EOL;
            file_put_contents('../var/log/saytest_error' . date("y-m-d-G-i-s") . '.log', $log, FILE_APPEND);

            $email = new MailerController();
            $email->sendEmail($this->mailer, "Un erreur dans l'exécution du job dont la commande est app:saywow");

        }


        // $this->logger->info("Greeted: succes");
        //            $email = new MailerController();
        //            $email->sendEmail($this->mailer, "can't say hello world properly");
        //



        return(1);
    }
}

