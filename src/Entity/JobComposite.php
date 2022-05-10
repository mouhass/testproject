<?php

namespace Batchjobs\ManageBatchJobsBundle\Entity;
use App\Repository\JobCompositeRepository;
use Cron\CronExpression;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass=JobCompositeRepository::class)
 */
class JobComposite extends Job
{


    /**
     * @ORM\OneToMany(targetEntity=JobCron::class, mappedBy="relationHistJobComp")
     * @ORM\JoinColumn(nullable=false)
     */
    private $historiqueSousJob;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="jobComposites", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;



    /**
     * @ORM\ManyToMany(targetEntity=JobCron::class, inversedBy="jobComposites")
     */
    private $listSousJobs;

    /**
     * @ORM\ManyToMany(targetEntity=Admin::class, inversedBy="jobCompositeCreated")
     */
    private $listDestination;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;


    /**
     * @ORM\Column(type="string")
     */
    private $state;

    /**
     * @return mixed
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @param mixed $expression
     * @return JobComposite
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
        return $this;
    }

    /**
     * @ORM\Column(type="string")
     */
    private $expression;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $numerocomposite;

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     * @return JobComposite
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
        return $this;
    }

    public function __construct()
    {
        $this->listSousJobs = new ArrayCollection();
        $this->historiqueSousJob = new ArrayCollection();
        $this->listDestination = new ArrayCollection();
    }



    /**
     * @return Collection<int, JobCron>
     */
    public function getHistoriqueSousJob(): Collection
    {
        return $this->historiqueSousJob;
    }

    public function addHistoriqueSousJob(JobCron $historiqueSousJob): self
    {
        if (!$this->historiqueSousJob->contains($historiqueSousJob)) {
            $this->historiqueSousJob[] = $historiqueSousJob;
        }

        return $this;
    }

    public function removeHistoriqueSousJob(JobCron $historiqueSousJob): self
    {
        $this->historiqueSousJob->removeElement($historiqueSousJob);

        return $this;
    }

    public function getCreatedBy(): ?Admin
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Admin $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, JobCron>
     */
    public function getListSousJobs(): Collection
    {
        return $this->listSousJobs;
    }

    public function addListSousJob(JobCron $listSousJob): self
    {
        if (!$this->listSousJobs->contains($listSousJob)) {
            $this->listSousJobs[] = $listSousJob;
        }

        return $this;
    }

    public function removeListSousJob(JobCron $listSousJob): self
    {
        $this->listSousJobs->removeElement($listSousJob);

        return $this;
    }

    /**
     * @return Collection<int, Admin>
     */
    public function getListDestination(): Collection
    {
        return $this->listDestination;
    }

    public function addListDestination(Admin $listDestination): self
    {
        if (!$this->listDestination->contains($listDestination)) {
            $this->listDestination[] = $listDestination;
        }

        return $this;
    }

    public function removeListDestination(Admin $listDestination): self
    {
        $this->listDestination->removeElement($listDestination);

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function nextDateCron(string $expression){

        $cron = new CronExpression($expression);
        return $cron->getNextRunDate()->format('i G j n w');
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getNumerocomposite(): ?int
    {
        return $this->numerocomposite;
    }

    public function setNumerocomposite(int $numerocomposite): self
    {
        $this->numerocomposite = $numerocomposite;

        return $this;
    }
}
