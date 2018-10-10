<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminUserNewType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array(
                'label' => "Nom d'utilisateur",
                'attr' => array('class' => 'form-control')
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => array('label' => 'Mot de passe', 'attr' => array('class' => 'form-control')),
                'second_options' => array('label' => 'Vérification du mot de passe', 'attr' => array('class' => 'form-control')),
            ))
            ->add('email',null, array(
                    'label' => "Adresse email",
                    'attr' => array('class' => 'form-control') 
            ))
            ->add('role', ChoiceType::class, array(
                    'choices' => array(
                        'Utilisateur' => "ROLE_USER",
                        'Encadrant technique' => "ROLE_ADMIN",
                        'Administrateur' => "ROLE_SUPER_ADMIN",
                    ),
                    'label' => "Niveau d'autorisation",
                    'attr' => array('class' => 'form-control'),
                    'mapped' => false
            ))
            ->add('isActive', null, array(
                'label' => "Compte actif",
                'attr' => array('class' => 'form-check-input')
            ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'csrf_protection' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
