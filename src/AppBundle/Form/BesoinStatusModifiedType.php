<?php

namespace AppBundle\Form;

use AppBundle\Entity\Besoin;
use AppBundle\Entity\BesoinStatus;
use AppBundle\Entity\BesoinStatusModified;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BesoinStatusModifiedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('besoin', EntityType::class, [
            'class' => Besoin::class
        ])->add('user', EntityType::class, [
            'class' => User::class
        ])->add('date', DateTimeType::class, [
            'widget' => 'single_text',
            'data' => new \DateTime()
        ])->add('besoinStatus', EntityType::class, [
            'class' => BesoinStatus::class
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BesoinStatusModified::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_besoin_status_modified_type';
    }
}
