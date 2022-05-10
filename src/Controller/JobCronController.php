<?php
namespace Batchjobs\ManageBatchJobsBundle\Controller;
use App\Entity\JobCron;
use App\Entity\JobCronSearch;
use App\Form\CreateNewJobCronType;
use App\Form\EditJobCronType;
use App\Form\JobCronSearchType;
use App\Form\JobCronType;
use App\Message\LogCommand;
use App\Repository\HistoriqueRepository;
use App\Repository\JobCronRepository;
use Cron\CronExpression;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/job/cron")
 */
class JobCronController extends AbstractController
{
    private $jobCronRepo;
    private $manager;
    private $bus;
    public function __construct(JobCronRepository $jobCronRepo,EntityManagerInterface $manager,MessageBusInterface  $bus)
    {
        $this->jobCronRepo = $jobCronRepo;
        $this->manager = $manager;
        $this->bus = $bus;
    }


    /**
     * @Route("/", name="app_jobCron_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator,JobCronRepository $jobCronRepository, Request $request): Response
    {
        $search = new JobCronSearch();
        $form = $this->createForm(JobCronSearchType::class,$search);
        $form->handleRequest($request);

        $jobCron = $paginator->paginate($jobCronRepository->findSpecific($search), $request->query->getInt('page',1),4);


        return $this->render('JobCron/index.html.twig', [
            'jobCron' => $jobCron,
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="app_jobCron_new", methods={"GET", "POST"})
     */
    public function new(Request $request, JobCronRepository $jobCronRepository): Response
    {
        $jobCron = new JobCron();
        $form = $this->createForm(CreateNewJobCronType::class, $jobCron);
        $form->handleRequest($request);

        $jobCron->setState("NOUVEAU");
        $jobCron->setActif(1);
        $jobCron->setNumero(rand(1000,9999));
        if ($form->isSubmitted() && $form->isValid()) {
            $jobCronRepository->add($jobCron);

            return $this->redirectToRoute('app_jobCron_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('JobCron/new.html.twig', [
            'admin' => $jobCron,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_JobCron_show", methods={"GET"})
     */
    public function show(JobCron $jobCron): Response
    {
       // $jobCron = $this->jobCronRepo->findElementById('secondJobCron');
        return $this->render('JobCron/show.html.twig', [
            'JobCron' => $jobCron,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_JobCron_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, JobCron $jobCron, JobCronRepository $repository): Response
    {

        $form = $this->createForm(EditJobCronType::class, $jobCron);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->manager;
            $em->persist($jobCron);
            $em->flush();
            $this->addFlash('success',"un job cron a ete modifié avec succes");


            return $this->redirectToRoute('app_jobCron_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('JobCron/edit.html.twig', [
            'JobCron' => $jobCron,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/delete", name="app_JobCron_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, JobCron $jobCron, JobCronRepository $jobCronRepository): Response
    {
         if($jobCron->getJobComposites()->toArray()==[]) {
             $jobCronRepository->remove($jobCron);
         }
         else{

         }

        return $this->redirectToRoute('app_jobCron_index', [], Response::HTTP_SEE_OTHER);
        //return new Response(implode() );

    }


    /**
     * @Route("/{id}/execImme", name="app_JobCron_execute" , methods={"GET","POST"})
     */
    public function execImm(KernelInterface $kernel,JobCron $jobCron){
//        $jobCron->setState("en cours");
//        $this->manager->persist($jobCron);
//        $this->manager->flush();
//        $application = new Application($kernel);
//        $application->setAutoExit(false);
//        $input = new ArrayInput(array(
//            'command' => $jobCron->getScriptExec(),
//            'Related_job'=>$jobCron->getId()
//        ));
//
//        // Use the NullOutput class instead of BufferedOutput.
//        $output = new BufferedOutput();
//
//        $application->run($input, $output);
//
//        $content = $output->fetch();
//        $pr = new Process(sprintf('php bin/console %s %s',  $jobCron->getScriptExec(),$jobCron->getId()));
//        $pr->setWorkingDirectory(__DIR__ . '/../..');
//
//        $pr->start();
//        while ($pr->isRunning()) {
//            $jobCron->setState("en cours");
//            $this->manager->persist($jobCron);
//            $this->manager->flush();
//
//        }

        $message = new LogCommand($jobCron->getScriptExec(),$jobCron->getId(),"0","0");
        $this->bus->dispatch($message);
        $jobCron->setState("en cours");
        $this->manager->persist($jobCron);
        $this->manager->flush();
        return $this->redirectToRoute('app_jobCron_index', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @Route("/jareb/jareb" ,name="jareb_jareb" , methods={"GET"})
     */
    public function getComposite(JobCronRepository $repository)
    {
        $jobCron = $repository->findElementById(8);
        dd($jobCron);

    }

    /**
     * @Route("/{id}/downloadFile", name="app_JobCron_downloadFiles" ,methods={"GET"})
     */
    public function download(JobCron $jobCron,HistoriqueRepository $repository,KernelInterface $kernel){

        $historique = $repository->findByExampleField($jobCron);

        return $this->file($kernel->getProjectDir().max($historique)->getPath());
    }


    /**
     * @Route("/{id}/actifdesactif", name="app_JobCron_actifdesactif",methods={"GET","PUT"})
     */
    public function actifdesactif(JobCron $jobCron,EntityManagerInterface $manager){
        if($jobCron->getActif()){
            $jobCron->setActif(false);
            $manager->persist($jobCron);
            $manager->flush();
        }
        else{
            $jobCron->setActif(true);
            $manager->persist($jobCron);
            $manager->flush();
        }
        return $this->redirectToRoute('app_jobCron_index', [], Response::HTTP_SEE_OTHER);
    }




    /**
     * @Route("/{id}/date/",name="app_JobCron_date" , methods={"GET"})
     */
    public function giveDate(JobCronRepository $repository){
        return new Response($repository->giveDateTime());
    }

    /**
     * @Route("/{id}/nextDate/",name="app_JobCron_nextdate",methods={"GET"})
     */
    public function nextDate(JobCronRepository $repository){
        $jobCron = $repository->findElementById(13);
        $cron = new CronExpression($jobCron->getExpression());
//        return new Response($cron->getNextRunDate()->format('i G j n w'));
        echo date('i G j n w', strtotime('+1 minute'));
        echo $cron->getNextRunDate()->format('i G j n w');
        echo get_debug_type($cron->getNextRunDate()->format('i G j n w'));
        return new Response(date('i G j n w', strtotime('+1 minute'))==$cron->getNextRunDate()->format('i G j n w') ? 'yes':'no');

    }


}

