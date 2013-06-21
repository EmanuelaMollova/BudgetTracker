<?php

namespace Acme\BudgetTrackerBundle\Twig;

class NotificationsExtension extends \Twig_Extension
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }
    
    public function getFunctions()
    {
        return array(
            'notifications_count' => new \Twig_Function_Method($this, 'countNotifications'),
            'get_notifications' => new \Twig_Function_Method($this, 'getNotifications')
        );
    }

    public function countNotifications()
    {        
        
        $expense_repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('AcmeBudgetTrackerBundle:Expense');
        $category_repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('AcmeBudgetTrackerBundle:Category');
        $this->user = $this->container->get('security.context')->getToken()->getUser();
        $debt = $category_repository->findByNameUser($this->user, 'Debts');       
        $this->debts_id = $debt[0]->getId();
        
        $notifications = count($expense_repository->findByCategory($this->user, $this->debts_id));

        return $notifications;
    }
    
    public function getNotifications()
    {        
        $expense_repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('AcmeBudgetTrackerBundle:Expense');
        $category_repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('AcmeBudgetTrackerBundle:Category');
        $this->user = $this->container->get('security.context')->getToken()->getUser();
        $debt = $category_repository->findByNameUser($this->user, 'Debts');       
        $this->debts_id = $debt[0]->getId();

        $active_debts = $expense_repository->findByCategory($this->user, $this->debts_id);

        return $active_debts;
    }
    
    public function getName()
    {
        return 'notifications_extension';
    }
}