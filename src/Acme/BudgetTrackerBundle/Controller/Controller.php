<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

/*
 * Some methods used in all controllers
 */
class Controller extends BaseController
{
    //Frequently used variables
    protected $user;
    protected $em;
    protected $expense_repository;
    protected $category_repository;
    protected $month_repository;
    protected $bill_repository;
    protected $debts_id;
    protected $loans_id;
    protected $newcomer;
    protected $number_of_user_categories;

    /*
     * Sets repository
     */
    protected function setRepository($class)
    {
        return $this->getDoctrine()->getRepository('AcmeBudgetTrackerBundle:'.$class);
    }
    
    protected function setDLIDs()
    {
            $debt = $this->category_repository->findCategoriesByNameAndUser($this->user, 'Debts');
            $loan = $this->category_repository->findCategoriesByNameAndUser($this->user, 'Loans');

            $this->debts_id = $debt[0]->getId();
            $this->loans_id = $loan[0]->getId();
    }
    
    /*
     * Gives value to all or some of the frequently used variables
     */
    protected function setVariables($newcomer = true, $month = true, $em = true, $ids = true, $expense = true, $category = true, $bill = true)
    {
        $this->user = $this->container->get('security.context')->getToken()->getUser();
        
        if($em){
            $this->em = $this->getDoctrine()->getEntityManager();
        }
        
        if($expense){
            $this->expense_repository = $this->setRepository('Expense');
        }
        
        if($category){
            $this->category_repository = $this->setRepository('Category');
        }
        
        if($month){
            $this->month_repository = $this->setRepository('Month');
        }
        
        if($bill){
            $this->bill_repository = $this->setRepository('Bill');
        }
        
        if($ids){
            $debt = $this->category_repository->findCategoriesByNameAndUser($this->user, 'Debts');
            $loan = $this->category_repository->findCategoriesByNameAndUser($this->user, 'Loans');

            $this->debts_id = $debt[0]->getId();
            $this->loans_id = $loan[0]->getId();
        }
        
        if($newcomer){
            $this->number_of_user_categories = $this->category_repository->countCategoriesByUser($this->user);
            
            if($this->number_of_user_categories == 0){
                $this->newcomer = true;
            } else {
                $this->newcomer = false;
            }
        }     
    }
       
    /*
     * Sets a flash message.
     */
    protected function setFlash($text, $type = 'notice')
    {
        return $this->get('session')->setFlash($type, $text);
    } 
}