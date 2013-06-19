<?php

namespace Acme\BudgetTrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DebtLoanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text',array(
            'label'  => 'From/To:'
        ));
        
        $builder->add('description', 'text', array(
            'label' => 'Description (optional):',
            'required' => false
        ));
        
        $builder->add('price', 'number', array(
            'label' => 'Price:'
        ));
        
        $builder->add('date', 'text', array(
            'label' => 'Date:'
        ));
        
        $builder->add('category', 'choice', array(
            'choices'   => array(0 => 'Debt', 1 => 'Loan'),
            'label' => 'Category: ',
            'required' => true
        ));
    }

    public function getName()
    {
        return 'debtloan';
    }
}