<?php

namespace Acme\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);

        // add name field
        $builder
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            //->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                //'first_options' => array('label' => 'form.password', 'label_attr'=> array('class'=> 'col-lg-2 control-label required')),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));
        $builder->add('name');
        /*$builder->add('petition', 'hidden', array(
            'required' => false, 'mapped' => false
        ));*/
        $builder->add('petition', 'hidden');
        // remove username
        //$builder->remove('username');
    }

    public function getName()
    {
        return 'acme_user_registration';
    }
}