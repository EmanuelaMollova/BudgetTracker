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
        
        $dl_repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('AcmeBudgetTrackerBundle:DebtLoan');
        $this->user = $this->container->get('security.context')->getToken()->getUser();

        $notifications = $dl_repository->countNotReturned($this->user, 0);

        return $notifications;
    }
    
    public function getNotifications()
    {        
        
        $dl_repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('AcmeBudgetTrackerBundle:DebtLoan');
        $this->user = $this->container->get('security.context')->getToken()->getUser();

        $notifications = $dl_repository->findByCategory($this->user, 0, 0);

        return $notifications;
    }
    
    public function getName()
    {
        return 'notifications_extension';
    }
}