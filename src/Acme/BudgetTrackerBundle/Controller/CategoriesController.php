<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Category;
use Acme\BudgetTrackerBundle\Form\Type\CategoryType;

/*
 * Takes care of adding, editing, deleting and displaying categories
 */
class CategoriesController extends Controller
{
    
    //Sets value to most friquently used variables
    private function init()
    {
        $this->setVariables($newcommer = false, $month = false, $em = true, $expense = false, $category = true, $ids = false);
        $this->categories_for_user = $this->category_repository->findCategoriesByUser($this->user);
    }

    /*
     * Displays the form for adding new and all the existing cateogires
     */
    public function categoriesAction()
    {
        $this->init();

        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);

        return $this->render(
            'AcmeBudgetTrackerBundle:Categories:categories.html.twig', array(
                'categories' => $this->categories_for_user, 
                'form' => $form->createView()));
    }
   
    /*
     * Creates new categories
     */
    public function createCategoryAction(Request $request)
    {
        $this->init();

        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);

        if ($request->isMethod('POST')) {
            $form->bind($request);

            $same = $this->category_repository->
                countCategoriesByName($category->getName(), $this->user);

            if ($same == 0 && $form->isValid()) {
                $category->setUser($this->user);
                $this->em->persist($category);
                $this->em->flush();

                return $this->redirect($this->generateUrl('categories'));
            } else {
                $this->setFlash('This value is already used!');
            }
        }
                
        return $this->render(
            'AcmeBudgetTrackerBundle:Categories:categories.html.twig', array(
                'categories' => $this->categories, 
                'form' => $form->createView()));    
    }
    
    /*
     * Edits existing categories
     */
    public function editCategoryAction(Request $request, Category $category)
    {   
        $this->init();

        if ($request->isMethod('POST')) {
            $old_name = $category->getName();           
            $name = $request->get('value');

            $same = $this->category_repository->
                countCategoriesByName($name, $this->user);
            
            if ($same == 0) {
                $name = $request->get('value');
                $category->setName($name);

                $this->em->persist($category);
                $this->em->flush();
            } else { 
                $old_response = new Response($old_name);
                return $old_response;
            }
        } else {
            echo "Not post";
        }
        
        $new_response = new Response($name);
        return $new_response;     
    }
  
    /*
     * Deletes categories if there are no expenses for them
     */
    public function deleteCategoryAction($id)
    {  
        $this->init();
        
        $category = $this->category_repository->find($id);
        if(!$category){
            return $this->render(
                'AcmeBudgetTrackerBundle:Error:error.html.twig', array(
                'item' => 'category'));
        }
        
        $this->expense_repository = $this->setRepository('Expense');
        $expenses = $this->expense_repository->findExpensesByCategory($category, $this->user);
        
        if (count($expenses) == 0){           
            $this->em->remove($category);
            $this->em->flush();
        } else {
            $this->setFlash('You cannot delete this category because there are some expenses for it.');
        }
        
        return $this->redirect($this->generateUrl('categories'));
    }
}