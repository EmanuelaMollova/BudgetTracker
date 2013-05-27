<?php

namespace Acme\BudgetTrackerBundle\Controller;
use Acme\BudgetTrackerBundle\Entity\Report;
use Acme\BudgetTrackerBundle\Form\Type\ReportType;
use Symfony\Component\HttpFoundation\Request;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

class ReportsController extends Controller
{
    public function reportsAction()
    {
         $this->user = $this->container->get('security.context')->getToken()->getUser();
        $cat_repo = $this->setRepository('Category');
        $all_categories = $cat_repo->findByUser($this->user);
        
        $repo = $this->setRepository('Expense');
        $exp = $repo->findBetweenDates($this->user, '14-05-2013	', '21-05-2013');
        
        //var_dump($exp);
        
        $report = new Report();
        $form = $this->createForm(new ReportType($this->user), $report);
        
        return $this->render('AcmeBudgetTrackerBundle:Reports:reports.html.twig', array(
            'all_categories' => $all_categories,
            'exp' => $exp,
            'form' => $form->createView()
        ));
    }
    
        public function takeReportAction(Request $request)
    {
        $this->em = $this->getEM();
        $this->user = $this->container->get('security.context')->getToken()->getUser();
        
        $report = new Report();
        $form = $this->createForm(new ReportType($this->user), $report);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            $params = $request->request->all();
            
//            var_dump($params['month']['categories']);
//            var_dump($params['month']['start_date']);
//            var_dump($params['month']['end_date']);

            
            die();
           
           return $this->render(
            'AcmeBudgetTrackerBundle:Reports:reports.html.twig', array(
                'request' => $request,
                'report' => $report,
                'form' => $form->createView()));    
        }
            
//            if ($same == 0 && $form->isValid()) {
//                $month->setUser($this->user);
//                $this->em->persist($month);
//                $this->em->flush();

//                return $this->redirect($this->generateUrl('months'));
//            } else {
//                echo "YOU BAD PERSON!!!";
//            }
        }
}