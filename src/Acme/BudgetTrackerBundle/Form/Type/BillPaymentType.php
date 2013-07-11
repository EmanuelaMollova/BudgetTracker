<?php

namespace Acme\BudgetTrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BillPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('description', 'text', array(
            'label' => 'Description (optional):',
            'required' => false
        ));
        
        $builder->add('price', 'number', array(
            'label' => 'Price:'
        ));
        
        $builder->add('date_when_paid', 'text', array(
            'label' => 'Date when paid:'
        ));
        
        $builder->add('date_to_pay_again', 'text', array(
            'label' => 'Date to pay again:'
        ));

    }

    public function getName()
    {
        return 'bill_payment';
    }
}