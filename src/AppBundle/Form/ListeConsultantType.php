<?php

namespace AppBundle\Form;

use AppBundle\Entity\Besoin;
use AppBundle\Entity\ListeConsultant;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListeConsultantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', EntityType::class, [
            'class' => User::class,
        ])->add('besoin', EntityType::class, [
            'class' => Besoin::class
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ListeConsultant::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_liste_consultant_type';
    }
}
