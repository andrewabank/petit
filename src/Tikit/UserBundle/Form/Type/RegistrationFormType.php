<?php

namespace Tikit\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add name field
        $builder->add('name');
        // remove username
        $builder->remove('username');
    }

    public function getName()
    {
        return 'acme_user_registration';
    }
}