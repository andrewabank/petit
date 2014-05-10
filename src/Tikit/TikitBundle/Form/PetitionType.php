<?php

namespace Tikit\TikitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PetitionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('petitionTitle')
            ->add('petitionDescription')
            //->add('petitionUrl', 'hidden', array('mapped' => false))
            //->add('dateAdded')
            ->add('user', 'hidden')
            ->add('category')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tikit\TikitBundle\Entity\Petition'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tikit_tikitbundle_petition';
    }
}
