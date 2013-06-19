<?php

namespace Acme\BudgetTrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TransferType extends AbstractType
{     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('money', 'number', array(
            'label' => 'Transfer'
        ));    
        
        
        $builder->add('destination', 'text',array(
            'label'  => 'to'
        ));
    }

    public function getName()
    {
        return 'transfer';
    }
}
