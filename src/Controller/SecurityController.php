<?php


namespace Batchjobs\ManageBatchJobsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login",name="login")
     */

    public function Login(AuthenticationUtils $authenticationUtils){
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('Security/login.html.twig', [
            'last_username'=>$lastUsername,
            'error'=>$error
        ]);
    }
}
