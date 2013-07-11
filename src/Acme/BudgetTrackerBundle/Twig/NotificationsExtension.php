<?php

namespace Acme\BudgetTrackerBundle\Twig;

class NotificationsExtension extends \Twig_Extension
{
    private $container;
    
    public function __construct($container) 
    {
        $this->container = $container;
    }
    
    public function getFunctions()
    {
        return array(
            'notifications_count' => new \Twig_Function_Method($this, 'countNotifications'),
            'debts_count' => new \Twig_Function_Method($this, 'countDebts'),
            'bills_count' => new \Twig_Function_Method($this, 'countBills'),
            'get_debts' => new \Twig_Function_Method($this, 'getDebts'),
            'get_bills' => new \Twig_Function_Method($this, 'getBills'));
    }
    
    private $expense_repository;
    private $category_repository;
    private $bill_repository;
    private $user;
    private $debts;
    private $bills;
    
    private function init($debts = true, $bills = true)
    {
        $this->user = $this->container->get('security.context')->getToken()->getUser();
        
        if($debts){
            $this->expense_repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('AcmeBudgetTrackerBundle:Expense');
            $this->category_repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('AcmeBudgetTrackerBundle:Category');
            
            $debt = $this->category_repository->findCategoriesByNameAndUser($this->user, 'Debts');       
            $this->debts_id = $debt[0]->getId();
        
            $this->debts = $this->expense_repository->findExpensesByCategory($this->user, $this->debts_id);
        }
        
        if($bills){
            $this->bill_repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('AcmeBudgetTrackerBundle:Bill');
            
            $date = new \DateTime();
            $date = $date->modify('+10 day');
        
            $this->bills = $this->bill_repository->findBillsToBePaid($this->user, $date);     
        }
    }

    public function countDebts($debts = true, $bills = false)
    {
        $this->init();
        $debts_number = count($this->debts);
        
        return $debts_number;      
    }
    
    public function countBills($debts = false, $bills = true)
    {
        $this->init();
        $bills_number = count($this->bills);
        
        return $bills_number;            
    }

    public function countNotifications()
    {        
        return $this->countBills() + $this->countDebts();
    }
    
    public function getDebts($debts = true, $bills = false)
    {       
        return $this->debts;
    }
    
    public function getBills($debts = false, $bills = true)
    {
        return $this->bills;
    }
    
    public function getName()
    {
        return 'notifications_extension';
    }
}