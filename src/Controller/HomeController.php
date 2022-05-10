<?php

namespace Batchjobs\ManageBatchJobsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/",name="Home")
     */
     public function index(){
         return $this->render('tous.html.twig');
     }
}
