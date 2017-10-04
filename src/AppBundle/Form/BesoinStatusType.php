<?php

namespace AppBundle\Form;

use AppBundle\Entity\BesoinStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BesoinStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BesoinStatus::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_besoin_status_type';
    }
}
