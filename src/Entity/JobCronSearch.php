<?php

namespace Batchjobs\ManageBatchJobsBundle\Entity;

class JobCronSearch
{
    private $numero;
    private $command;

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }


    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $command
     * @return JobCronSearch
     */
    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }



}
