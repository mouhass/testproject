<?php
namespace Batchjobs\ManageBatchJobsBundle\Command;
use App\Controller\MailerController;
use App\Entity\Historique;
use App\Entity\JobComposite;
use App\Entity\JobCron;
use App\Repository\JobCronRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Mailer\MailerInterface;

class StructCommand
{
    protected $manager;
    protected $managerRegistry;
    protected $repository;
    protected $mailer;
    public function __construct(EntityManagerInterface $manager,ManagerRegistry $managerRegistry,JobCronRepository $repository,MailerInterface $mailer)
    {$this->manager = $manager;
        $this->managerRegistry = $managerRegistry;
    $this->repository = $repository;
    $this->mailer = $mailer;
    }

    public function ajoutHistoriqueSucces(InputInterface $input,JobCron $jobCron){
        $historique = new Historique();
        $historique->setCreatedAt(new \DateTime());
        if ($input->getArgument('code_job_composite') == "0") {$historique->setPath('/var/log/saywow_succes' . date("y-m-d-G-i-s") . '.log');}
        else {$historique->setPath('/var/log/saywow_succes' . $input->getArgument('code_job_composite') . date("y-m-d-G-i-s") . '--' . $input->getArgument('dernier_Sous_Job') . '.log');}
        $historique->setJobCronHist($jobCron);
        $this->manager->persist($historique);
        $this->manager->flush();
    }

    public function ajoutHistoriqueError(InputInterface $input,JobCron $jobCron ){
        $historique = new Historique();
        $historique->setCreatedAt(new \DateTime());
        $historique->setPath('/var/log/saywow_error' . date("y-m-d-G-i-s") . '.log');


        $jobCron = $this->repository->findElementById($input->getArgument('Related_job'));
        $historique->setJobCronHist($jobCron);
        $this->manager->persist($historique);
        $this->manager->flush();
    }
    public function modifierEtatJobCronSucces(JobCron $jobCron){
        $jobCron->setState("Succès");
        $this->manager->persist($jobCron);
        $this->manager->flush();
    }
    public function modifierEtatJobCronError(JobCron $jobCron){
        $jobCron->setState("erreur");
        $this->manager->persist($jobCron);
        $this->manager->flush();
    }
    public function modifierEtatJobCompositeSucces(JobComposite $jobComposite){

        if ($jobComposite->getState() != "Erreur") {
            $jobComposite->setState("Succès");
            $this->manager->persist($jobComposite);
            $this->manager->flush();
        }
    }

    public function modifierEtatJobCompositeError(InputInterface $input,JobComposite $jobComposite){
        $jobComposite->setState("Erreur");
        $this->manager->persist($jobComposite);
        $this->manager->flush();
    }

    public function EnvoyerEmailErrorComposite(JobComposite $jobComposite,JobCron $jobCron){
        $email = new MailerController();
        $email->sendEmail($this->mailer, "Une erreur dans l'exécution du job composite dont le numero  est ".$jobComposite->getNumerocomposite()." dans le sous job qui possède la commande ".$jobCron->getScriptExec()." et le numéro " . $jobCron->getNumero());
    }

    public function EnvoyerEmailErrorCron(JobCron $jobCron){
        $email = new MailerController();
        $email->sendEmail($this->mailer, "Une erreur dans l'exécution du job cron dont la commande est app:saywow et le numéro " . $jobCron->getNumero());
    }
}
