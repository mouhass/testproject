<?php

namespace Batchjobs\ManageBatchJobsBundle\Entity;

class JobCompositeSearch
{

    private $numerocomposite;
    private $nameSousJob;

    /**
     * @return mixed
     */
    public function getNameSousJob()
    {
        return $this->nameSousJob;
    }

    /**
     * @param mixed $nameSousJob
     * @return JobCompositeSearch
     */
    public function setNameSousJob($nameSousJob)
    {
        $this->nameSousJob = $nameSousJob;
        return $this;
    }
    private $expression;

    /**
     * @return mixed
     */
    public function getNumerocomposite()
    {
        return $this->numerocomposite;
    }


    public function setNumerocomposite($numerocomposite)
    {
        $this->numerocomposite = $numerocomposite;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @param mixed $expression
     * @return JobCompositeSearch
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
        return $this;
    }



}
