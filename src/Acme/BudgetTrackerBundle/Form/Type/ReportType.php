<?php

namespace Acme\BudgetTrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ReportType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('start_date', 'text',array(
            'label'  => 'Start date'
        ));
        
        $builder->add('end_date','text', array(
            'label' => 'End date'
        )); 
    }

    public function getName()
    {
        return 'month';
    }
}
