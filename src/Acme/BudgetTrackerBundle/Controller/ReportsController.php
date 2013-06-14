<?php

namespace Acme\BudgetTrackerBundle\Controller;
use Acme\BudgetTrackerBundle\Entity\Report;
use Acme\BudgetTrackerBundle\Form\Type\ReportType;
use Symfony\Component\HttpFoundation\Request;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

class ReportsController extends Controller
{
    public function reportsAction(Request $request)
    {   $response = null;
         $this->user = $this->container->get('security.context')->getToken()->getUser();
        //$cat_repo = $this->setRepository('Category');
        //$all_categories = $cat_repo->findByUser($this->user);
        
        $repo = $this->setRepository('Expense');
        //$exp = $repo->findBetweenDates($this->user, '14-05-2013', '21-05-2013');
        
        //var_dump($exp);
        
        $report = new Report();
        $today = new \DateTime();
        $date = $today->format('d-m-Y');
        $report->setEndDate($date);
        $form = $this->createForm(new ReportType($this->user), $report);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
        
            $params = $request->request->all();

            
            $cats = $params['month']['categories'];

            //create query for categories

            
            
            $start_date = $params['month']['start_date'];
            $end_date = $params['month']['end_date'];
            
            $start_dateObj = \DateTime::createfromformat('d-m-Y', $start_date);
            $start_dateObj->setTime(0, 0, 0);
            
            $end_dateObj = \DateTime::createfromformat('d-m-Y', $end_date);
            $end_dateObj->setTime(0, 0, 0);
            $end_dateObj->modify('+1 day');

//            echo 'Start Date Obj <br>';
//            var_dump($start_dateObj); echo '<br>';
//            echo 'End Date Obj <br>';
//            var_dump($end_dateObj); echo '<br>';
            
            $query = '';
            
            //finish the query for time
            
//            if( $start_date != ""){
//                $query .= " AND e.date >= ".$start_dateObj;
//            }
            
            $query .=' AND (e.category='.$cats[0];
            
            array_shift($cats);
            foreach ($cats as $c)
            {
                $query .= ' OR e.category='.$c;
            }
            
            $query .= ')';
            
         //   echo $query;
            $response = $repo->findForCatsAndTimes($this->user, $start_dateObj, $end_dateObj, $query); 
            
            //$response = $repo->findBetweenDates($this->user, $start_date, $end_date);
        }
        

        return $this->render('AcmeBudgetTrackerBundle:Reports:reports.html.twig', array(
            //'all_categories' => $all_categories,
            //'exp' => $exp,
            'response' => $response,
            'form' => $form->createView()
        ));
    }
    
    /*public function takeReportAction(Request $request)
    {
        //$this->em = $this->getEM();
        //$this->user = $this->container->get('security.context')->getToken()->getUser();
        
        $report = new Report();
        $form = $this->createForm(new ReportType($this->user), $report);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            $params = $request->request->all();
            
            $cats = $params['month']['categories'];
            
            //var_dump($cats);
            $query='';
            foreach ($cats as $c)
            {
                $query .= ' AND e.category='.$c;
            }
            
          //  var_dump($query);
            
            $this->user = $this->container->get('security.context')->getToken()->getUser();
        
            $repo = $this->setRepository('Expense');
            
            $response = null;
            
            $response = $repo->findForCat($this->user, $query);
            
            //var_dump($a);
            //die();
        }
//            var_dump($params['month']['start_date']);
//            var_dump($params['month']['end_date']);
           // die();
           
           return $this->render(
            'AcmeBudgetTrackerBundle:Reports:reports.html.twig', array(
                'request' => $request,
                'report' => $report,
                'response' => $response,
                'form' => $form->createView()));    
        }
            
//            if ($same == 0 && $form->isValid()) {
//                $month->setUser($this->user);
//                $this->em->persist($month);
//                $this->em->flush();

//                return $this->redirect($this->generateUrl('months'));
//            } else {
//                echo "YOU BAD PERSON!!!";
//            } */
        
}