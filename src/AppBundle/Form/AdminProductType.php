<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class AdminProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title',null, array(
            'label' => "Titre de l'annonce",
            'attr' => array('class' => 'form-control')
        ))
        ->add('description', null, array(
            'label' => "Déscription du produit",
            'attr' => array('class' => 'form-control', 'rows' => '8')
        ))
        ->add('imageFile', VichFileType::class, array(
            'required' => false,
        ))
        ->add('ref', null, array(
            'label' => "Référence de l'annonce",
            'attr' => array('class' => 'form-control')
        ))
        ->add('autoDeleteAt',null, array(
            'label' => 'Date de mise hors ligne',
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'format' => 'dd-MM-yyyy HH:mm',
            'input' => 'datetime',
            'attr' => array('data-date-format' => 'dd-MM-yyyy HH:mm',
                // 'class' => 'form-control'
            ),
        ))
        ->add('city', null, array(
            'label' => "Emplacement du prduit",
            'attr' => array('class' => 'form-control')
        ))
        ->add('tags', null, array(
            'label' => "Tags liés",
            'expanded' => true,
            'multiple' => true,
            'required' => true,
            'choice_attr' => function ($choiceValue, $key, $value) {
                return ['class' => 'form-check-input'];
            },

        ))
        ->add('category', null, array(
            'label' => "Catégorie du produit",
            'attr' => array('class' => 'form-control')
        ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
