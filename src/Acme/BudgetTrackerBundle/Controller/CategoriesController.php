<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\BudgetTrackerBundle\Entity\Category;
use Acme\BudgetTrackerBundle\Form\Type\CategoryType;
use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

class CategoriesController extends Controller
{
    //Most frequently used variables
    private $em;
    private $repository;
    private $categories;
    
    //Sets value to most friquently used variables
    private function init()
    {
        $this->em = $this->getEM();
        $this->setUser();
        $this->repository = $this->setRepository('Category');
        $this->categories = $this->repository->findByUser($this->user);
    }

    //Displays all categories
    public function categoriesAction()
    {
        $this->init();
        
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);

        $id = null;
        
        return $this->render(
            'AcmeBudgetTrackerBundle:Categories:categories.html.twig', array(
                'categories' => $this->categories, 
                'id' => $id, 
                'form' => $form->createView()));
    }
   
    //Creates new category
    public function createCategoryAction(Request $request)
    {
        $this->init();
        $id = null;

        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);

        if ($request->isMethod('POST')) {
            $form->bind($request);

            $same = $this->repository->
                    countByNameAndUser($category->getName(), $this->user);

            if (count($same) == 0 && $form->isValid()) {
                $category->setUser($this->user);
                $this->em->persist($category);
                $this->em->flush();

                return $this->redirect($this->generateUrl('categories'));
            } else {
                $this->get('session')->setFlash('notice', 'This value is already used!');
            }
        }
                
        return $this->render(
            'AcmeBudgetTrackerBundle:Categories:categories.html.twig', array(
                'categories' => $this->categories, 
                'id' => $id, 
                'form' => $form->createView()));    
    }
    
public function editCategoryAction(Request $request, Category $category)
{   
    //check if exists/valid

    //get the text sent from jeditable
    $name = $request->get('value');
    $category->setName($name);

    $em = $this->getDoctrine()->getManager();
    $em->persist($category);
    $em->flush();
    //return the name value to jeditable so it can display it
    return new Response($name);     
}
   
    //Edits existing category
//    public function editCategoryAction(Request $request, $id)
//    {
//        $this->init();
//        
//        $category = $this->repository->find($id);      
//        $form = $this->createForm(new CategoryType(), $category);
//        
//        if ($request->isMethod('POST')) {
//            $form->bind($request);
//
//            if ($form->isValid()) {
//                //echo 'VALIDDD';
//                //die();
//                $this->em->persist($category);
//                $this->em->flush();
//                
//                //return $this->redirect($this->generateUrl('categories'));
//            }
//        }
//        
//        return $this->render(
//            'AcmeBudgetTrackerBundle:Categories:categories.html.twig', array(
//                'categories' => $this->categories, 
//                'id' => $id, 
//                'form' => $form->createView()));      
//    }
    
    //Deletes category
    public function deleteCategoryAction($id)
    {  
        $this->init();
        
        $category = $this->repository->find($id);
        
        $this->em->remove($category);
        $this->em->flush();
        
        return $this->redirect($this->generateUrl('categories'));
    }
}