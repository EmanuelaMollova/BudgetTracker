<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Bill;
use Acme\BudgetTrackerBundle\Form\Type\BillType;
use Acme\BudgetTrackerBundle\Entity\BillPayment;
use Acme\BudgetTrackerBundle\Form\Type\BillPaymentType;

class BillsController extends Controller
{ 
    /*
     * Sets most used variables
     */
    private function init()
    {
        $this->setVariables($newcomer = false, $month = false, $em = true, $ids = false, $expense = false, $category = false);
        $this->bill_repository = $this->setRepository('Bill');
    }

    /*
     * Displays bills and creates new ones
     */
    public function billsAction(Request $request)
    {
        $this->init();      
        $bills_for_user = $this->bill_repository->findBillsByUser($this->user);

        $bill = new Bill();
        $form = $this->createForm(new BillType(), $bill);
        
        $last_day_this_month = date('m-t-Y');
        $last_day_this_month_obj = \DateTime::createfromformat('m-d-Y', $last_day_this_month);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $same = $this->bill_repository->countBillsByName($bill->getName(), $this->user);  
            
            if ($same == 0 && $form->isValid()) {
                $bill->setUser($this->user);
                $this->em->persist($bill);
                $this->em->flush();

                return $this->redirect($this->generateUrl('bills'));
            } else {
                $this->setFlash('This value is already used!');
            }
        }
                
        return $this->render(
            'AcmeBudgetTrackerBundle:Bills:bills.html.twig', array(
                'bills_for_user' => $bills_for_user,
                'last_day_this_month' => $last_day_this_month_obj,
                'form' => $form->createView()));    
    }
    
    /*
     * Takes care for bills payments - adds new payments, displays old ones and
     * shows when the next payment should be made
     */
    public function paymentsAction(Request $request, $id)
    {
        $this->init();
        
        $payments = $this->bill_payment_repository->findPaymentsByBill($this->user, $id);
        $sum_of_payments = $this->bill_payment_repository->findSumOfPaymentsByBill($this->user, $id);
        
        $bill = $this->bill_repository->findById($id);
        $bill = $bill[0];

        $today = new \DateTime('now');
        $today = $today->format('d-m-Y');
        
        $to_pay_date = new \DateTime();
        $to_pay_date->modify('+1 month');
        $to_pay_date = $to_pay_date->format('d-m-Y');
        
        $bill_payment = new BillPayment();
        $bill_payment->setDateWhenPaid($today);
        $bill_payment->setDateToPayAgain($to_pay_date);
        $form = $this->createForm(new BillPaymentType(), $bill_payment);
 
         if ($request->isMethod('POST')) {
            $form->bind($request);
            
            //Convert string date to DateTime object and send it to the database as object
            $date_when_paid_object = \DateTime::createfromformat('d-m-Y', $bill_payment->getDateWhenPaid());
            $bill_payment->setDateWhenPaid($date_when_paid_object);
            $date_to_pay_again_object = \DateTime::createfromformat('d-m-Y', $bill_payment->getDateToPayAgain());
            $bill_payment->setDateToPayAgain($date_to_pay_again_object);
            
            if($bill->getDateToPayAgain() == null || $bill->getDateToPayAgain() < $bill_payment->getDateToPayAgain()){
                $bill->setDateToPayAgain($date_to_pay_again_object);
                $this->em->persist($bill);
            }
            
            if ($form->isValid()) {               
                $bill_payment->setUser($this->user);
                $bill_payment->setBill($bill);

                $this->em->persist($bill_payment);               
                $this->em->flush();

                return $this->redirect($this->generateUrl('payments', array('id' => $id)));
            }
            
            return $this->render(
            'AcmeBudgetTrackerBundle:Bills:bill_payments.html.twig', array(
                'bill' => $bill, 
                'form' => $form->createView())); 
            
        } else {
        
        return $this->render(
            'AcmeBudgetTrackerBundle:Bills:bill_payments.html.twig', array(
                'bill' => $bill,
                'payments' => $payments,
                'sum_of_payments' => $sum_of_payments,
                'form' => $form->createView())); 
        }
    }
}