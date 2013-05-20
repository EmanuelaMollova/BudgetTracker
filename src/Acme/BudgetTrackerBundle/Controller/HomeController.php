<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $newcommer = true;
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $repository = $this->getDoctrine()
            ->getRepository('AcmeBudgetTrackerBundle:Category');
        
        $number_of_categories = $repository->countByUser($user);
        
        if($number_of_categories){
            $newcommer = false;
        }
        
        return $this->render('AcmeBudgetTrackerBundle:Home:index.html.twig',
                array('newcommer' => $newcommer));
    }
}