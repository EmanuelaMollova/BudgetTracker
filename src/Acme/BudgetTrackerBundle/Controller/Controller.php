<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

class Controller extends BaseController
{
    protected $user;
    protected $notifications;
    protected $debts_id;
    protected $loans_id;
    
    protected function setUser()
    {
        $this->user = $this->container->get('security.context')->getToken()->getUser();
    }
    
    protected function setDebtsLoansIds()
    {
        $category_repository = $this->setRepository('Category');
        
        $debt = $category_repository->findByNameUser($this->user, 'Debts');
        $loan = $category_repository->findByNameUser($this->user, 'Loans');
        
        $this->debts_id = $debt[0]->getId();
        $this->loans_id = $loan[0]->getId();
    }





    /*
     * Gets EntityManager
     */
    protected function getEM()
    {
        return $this->getDoctrine()->getEntityManager();
    }
    
    protected function setRepository($class)
    {
        return $this->getDoctrine()->getRepository('AcmeBudgetTrackerBundle:'.$class);
    }
    
//    /*
//     * Sets a flash message.
//     */
//    protected function setFlash($text, $type = 'notice')
//    {
//        return $this->get('session')->setFlash($type, $text);
//    }  
//    
//    /*
//     * Finds an entity by its id.
//     */
//    protected function findById($id, $class)
//    {
//        $result = $this->getEM()->getRepository('AcmeBudgetTrackerBundle:'.$class)->find($id);   
//        if($result == null) {
//            return false;
//        } else {
//            return $result;
//        }
//    }
//    
//    /*
//     * Checks if there already is a category with the same name.
//     * 
//     */
//    protected function checkDuplication($name, $url)
//    {
//        $count = $this->getEM()->getRepository('EMExpensesBundle:Category')
//                                    ->countCategories($name);
//            if($count != 0) {
//                $this->get('session')->setFlash('error', 'There already is a category with this name!');
//                
//                return $this->redirect($url); 
//            }
//    }
}