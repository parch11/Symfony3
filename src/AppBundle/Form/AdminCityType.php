<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminCityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => "Nom de la vlle",
                'attr' => array('class' => 'form-control')
            ))
            ->add('address', null, array(
                'label' => "Adresse complète (N° + Rue + Code Postal + Ville)",
                'attr' => array('class' => 'form-control')
            ))
            ->add('contactMessage', null, array(
                'label' => "Message de contact associé à cette adresse",
                'attr' => array('class' => 'form-control')
            ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\City'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_city';
    }


}
