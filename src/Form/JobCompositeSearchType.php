<?php

namespace Batchjobs\ManageBatchJobsBundle\Form;

use App\Entity\JobCompositeSearch;
use App\Entity\JobCronSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobCompositeSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numerocomposite',TextType::class,['required'=>false,
                'label'=>false, 'attr'=>['placeholder'=>'Le numero']])
            ->add('expression', TextType::class,['required'=>false,
                'label'=>false, 'attr'=>['placeholder'=>'La fréquence ']])
            ->add('nameSousJob',TextType::class,['required'=>false,
                'label'=>false, 'attr'=>['placeholder'=>'Le nom du sous job']])
            ->add('submit',SubmitType::class,['label'=>'Rechercher'] )
        ;    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobCompositeSearch::class,
            'method'=>'get',
            'csrf_protection'=>false,
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
