<?php

namespace AppBundle\Form;

use AppBundle\Entity\Besoin;
use AppBundle\Entity\Contact;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BesoinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
            ->add('description')
            ->add('keySuccess')
            ->add('active')
            ->add('contact', EntityType::class, [
                'class' => Contact::class,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class
            ])
            ->add('rate')
            ->add('location')
            ->add('dateCreate', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime()
            ])
            ->add('startAtLatest', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime()
            ])
            ->add('duration')
            ->add('frequency')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Besoin::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_besoin_type';
    }
}
