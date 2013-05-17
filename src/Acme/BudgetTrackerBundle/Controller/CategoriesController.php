<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\BudgetTrackerBundle\Entity\Category;
use Acme\BudgetTrackerBundle\Form\Type\CategoryType;
use Acme\BudgetTrackerBundle\Controller\Controller as BaseController;

class CategoriesController extends BaseController
{
    //Most frequently used variable
    private $em;
    private $user;
    private $repository;
    
    //Set value to most friquently used variables
    private function init()
    {
        $this->em = $this->getEM();
        $this->user = $this->container->get('security.context')->getToken()->getUser();
        $this->repository = $this->setRepository('Category');
    }

    //Displays all categories
    public function categoriesAction()
    {
        $this->init();
        
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);

        $categories = $this->repository->findByUser($this->user);
        
        $id = null;
        
        return $this->render(
            'AcmeBudgetTrackerBundle:Categories:categories.html.twig', 
            array('categories' => $categories, 'id' => $id, 
                  'form' => $form->createView()));
    }
   
    public function createCategoryAction(Request $request)
    {
        $this->init();
        
        $id = null;
        
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);
        
        $categories = $this->repository->findByUser($this->user);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            if ($form->isValid()) {
                $category->setUser($this->user);
                $this->em->persist($category);
                $this->em->flush();

                return $this->redirect($this->generateUrl('categories'));
            }
            
            return $this->render(
                'AcmeBudgetTrackerBundle:Categories:categories.html.twig', 
                array('categories' => $categories, 
                      'id' => $id, 
                      'form' => $form->createView()));
                
        }
  
//        $product = $repository->countByNameAndUser($category->getName(), $user);
//
//        if (count($product)==0 && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $category->setUser($user);
//            $em->persist($category);
//            $em->flush();
//
//            return $this->redirect($this->generateUrl('categories'));
//        } else {
//            echo "There is such cat";
//        }
        
        return $this->render(
            'AcmeBudgetTrackerBundle:Categories:categories.html.twig', 
            array('categories' => $categories, 
                  'id' => $id, 
                  'form' => $form->createView()));
      
    }
   
    public function editCategoryAction(Request $request, $id)
    {
        $this->init();
        
        $categories = $this->repository->findByUser($this->user);
        
        $category = $this->repository->find($id);
        
        $form = $this->createForm(new CategoryType(), $category);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $this->em->persist($category);
                $this->em->flush();
                
                return $this->redirect($this->generateUrl('categories'));
            }   
            return $this->render(
                'AcmeBudgetTrackerBundle:Categories:categories.html.twig', 
                array('categories' => $categories, 
                      'id' => $id, 
                      'form' => $form->createView()));
        }
        return $this->render(
            'AcmeBudgetTrackerBundle:Categories:categories.html.twig', 
            array('categories' => $categories, 
                  'id' => $id, 
                  'form' => $form->createView()));      
    }
    
    public function deleteCategoryAction($id)
    {  
        $this->init();
        
        $category = $this->repository->find($id);
        
        $this->em->remove($category);
        $this->em->flush();
        
        return $this->redirect($this->generateUrl('categories'));
    }
}