<?php

namespace Tikit\TikitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PetitionType extends AbstractType
{
    public function __construct($em) {
        $this->em = $em;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('petitionTitle')
            ->add('petitionDescription','textarea')
            //->add('petitionUrl', 'hidden', array('mapped' => false))
            //->add('dateAdded')
            //->add('user', 'hidden')
            ->add('category', 'entity', array(
    'class' => 'TikitTikitBundle:Category',
    'property'=>'categoryName',
    'label' => 'Адресат*: ',
    'attr'=>array('style'=>'width:300px'),
    'required' => false,
    'data'=>$this->em->getReference("TikitTikitBundle:Category",1) ))
            //->add('submit', 'submit', array('label' => 'Зберегти', 'attr' => array('class' => 'save')));
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
