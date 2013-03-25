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
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AcmeBudgetTrackerBundle:Category')->countByName('Food', $user);

        //var_dump($product);
        //die();
      
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);

        $repository = $this->getDoctrine()
            ->getRepository('AcmeBudgetTrackerBundle:Category');
        
        $categories = $repository->findByUser($user);
        
        $id = null;
        
        return $this->render('AcmeBudgetTrackerBundle:Categories:categories.html.twig', array(
            'categories' => $categories, 'id' => $id, 'form' => $form->createView()));
    }
   
    public function createCategoryAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);
        
        $repository = $this->getDoctrine()
            ->getRepository('AcmeBudgetTrackerBundle:Category');
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $categories = $repository->findByUser($user);

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $category->setUser($user);
                $em->persist($category);
                $em->flush();

                return $this->redirect($this->generateUrl('categories'));
            }
            $id = null;
        
        return $this->render('AcmeBudgetTrackerBundle:Categories:categories.html.twig', array(
            'categories' => $categories, 'id' => $id, 'form' => $form->createView()));
        }
    }
    
    public function editCategoryAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AcmeBudgetTrackerBundle:Category');
        $user = $this->container->get('security.context')->getToken()->getUser();
        $categories = $repository->findByUser($user);
        
        $category = $repository->find($id);
        
        $form = $this->createForm(new CategoryType(), $category);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();
                
                return $this->redirect($this->generateUrl('categories'));
            }   
            return $this->render('AcmeBudgetTrackerBundle:Categories:categories.html.twig', array(
        'categories' => $categories, 'id' => $id, 'form' => $form->createView()));
        }
        return $this->render('AcmeBudgetTrackerBundle:Categories:categories.html.twig', array(
        'categories' => $categories, 'id' => $id, 'form' => $form->createView()));      
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