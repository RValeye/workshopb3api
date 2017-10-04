<?php

namespace AppBundle\Form;

use AppBundle\Entity\Besoin;
use AppBundle\Entity\BesoinModification;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BesoinModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', EntityType::class, [
            'class' => User::class,
        ])->add('besoin', EntityType::class, [
            'class' => Besoin::class
        ])->add('date', DateTimeType::class, [
            'widget' => 'single_text',
            'data' => new \DateTime()
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BesoinModification::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_besoin_modification_type';
    }
}
