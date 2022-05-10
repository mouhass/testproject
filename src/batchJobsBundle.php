<?php


namespace Batchjobs\ManageBundlesBundle;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class batchJobsBundle extends Bundle
{

public function build(ContainerBuilder $container){
parent::build($container);
//$container->registerForAutoconfiguration(HealthInterface::class)->addTag(HealthInterface::TAG);
}

}
