<?php
namespace Acme\BudgetTrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MonthType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', 'text',array(
            'label'  => 'Set your budget for'
        ));
        
        $builder->add('budget', 'number', array(
            'label' => 'Budget:'
        )); 
    }

    public function getName()
    {
        return 'month';
    }
}