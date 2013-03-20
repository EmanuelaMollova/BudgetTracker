<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\BudgetTrackerBundle\Entity\Category;
use Acme\BudgetTrackerBundle\Form\Type\CategoryType;


class CategoriesController extends Controller
{
    public function categoriesAction()
    {
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);
        
        $repository = $this->getDoctrine()
            ->getRepository('AcmeBudgetTrackerBundle:Category');
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $categories = $repository->findByUser($user);
        
        return $this->render('AcmeBudgetTrackerBundle:Categories:categories.html.twig', array(
            'categories' => $categories, 'form' => $form->createView()));
    }
   
    public function createCategoryAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);


        if ($request->isMethod('POST')) {
            $form->bind($request);
            
         $user = $this->container->get('security.context')->getToken()->getUser();

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $category->setUser($user);
                $em->persist($category);
                $em->flush();

                return $this->redirect($this->generateUrl('categories'));
            }
        }
    }
    
    public function editCategoryAction(Request $request, $id)
    {
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);


        if ($request->isMethod('POST')) {
            $form->bind($request);
            
         $user = $this->container->get('security.context')->getToken()->getUser();

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $category->setUser($user);
                $em->persist($category);
                $em->flush();

                return $this->redirect($this->generateUrl('categories'));
            }
        }
    }
    
    public function deleteCategoryAction($id)
    {  
        $repository = $this->getDoctrine()
            ->getRepository('AcmeBudgetTrackerBundle:Category');
        
        $category = $repository->find($id);
        
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($category);
        $em->flush();
        
        return $this->redirect($this->generateUrl('categories'));
    }
}