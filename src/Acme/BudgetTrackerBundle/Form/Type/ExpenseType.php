<?php

namespace Acme\BudgetTrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ExpenseType extends AbstractType
{
    protected $user;
    
    function __construct($user) 
    {
        $this->user = $user;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('product', 'text',array(
            'label'  => 'Product:'
        ));
        
        $builder->add('description', 'text', array(
            'label' => 'Description: (optional)',
            'required' => false
        ));
        
        $builder->add('price', 'number', array(
            'label' => 'Price:'
        ));
        
        $builder->add('date', 'text', array(
            'label' => 'Date:'
        ));
        
        $user = $this->user;
        $builder->add('category', 'entity', array(
            'class' => 'AcmeBudgetTrackerBundle:Category',
            'property' => 'name',
            'label' => 'Category: ',
            'required' => true,
            'query_builder' => function ($repository) use ($user)
                { return $repository->createQueryBuilder('cat')
                                    ->select('cat')
                                    ->where('cat.user = :user')
                                    //->orderBy('cat.name', 'ASC')
                                    ->setParameter('user', $user);
                } ));    
    }

    public function getName()
    {
        return 'expense';
    }
}