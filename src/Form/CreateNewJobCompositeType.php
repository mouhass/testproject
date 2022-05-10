<?php

namespace Batchjobs\ManageBatchJobsBundle\Form;

use App\Entity\Admin;
use App\Entity\JobComposite;
use App\Entity\JobCron;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateNewJobCompositeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('name',TextType::class)
            ->add('listDestination',EntityType::class,['class'=>Admin::class,'multiple'=>true])
            ->add('expression',TextType::class)
            ->add('listSousJobs',EntityType::class,['class'=>JobCron::class,'multiple'=>true ])
            ->add('createdBy',EntityType::class,['class'=>Admin::class])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobComposite::class,
        ]);
    }
}
