<?php

namespace Batchjobs\ManageBatchJobsBundle\Form;

use App\Entity\Admin;
use App\Entity\JobCron;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobCronType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero',TextType::class)
            ->add('name',TextType::class, ['required'=>false])
            ->add('expression', TextType::class,['required'=>false])
            ->add('state',TextType::class,['required'=>false])
            ->add('actif',TextType::class,['required'=>false])
            //->add('listDestination',EntityType::class,['class' => Admin::class,'multiple'=>true])
            ->add('createdBy', EntityType::class, ['class' => Admin::class, 'choice_label' => 'name' ])
//            ->add('nextDateExec',DateTimeType::class,['required'=>false])
            ->add('scriptExec',TextType::class)

        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults([
           'data_class' => JobCron::class,
       ]);
    }


}
