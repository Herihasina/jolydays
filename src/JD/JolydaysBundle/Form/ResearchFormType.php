<?php

namespace JD\JolydaysBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ResearchFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search', MultiSearchType::class, array(
                'class' => 'JDJolydaysBundle:Posts',
                'search_fields' => array(), 
                'search_comparison_type' = > 'wildcard'
                 
            ))
    }

}
