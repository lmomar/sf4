<?php

namespace App\Controller\Admin;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\DeleteCategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{

    /**
     * @Route(path="/categories")
     */
    public function index(CategoryRepository $repository){
        $categories = $repository->getCategories(0,2);
        
        return $this->render('admin/categories/index.html.twig',compact('categories'));
    }


    /**
     * @Route(path="/category/add")
     */
    public function add(Request $request){
        $cat = new Category();
        $form = $this->createForm(CategoryType::class,$cat);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($cat);
            $em->flush();
            $this->addFlash("success","Category Saved");
            return $this->redirectToRoute('app_admin_category_index');
        }
        return $this->render('admin/categories/add.html.twig',['form' => $form->createView()]);

    }

    /**
     * @Route(path="/category/edit/{id}",requirements={"id"="\d+"})
     */
    public function edit(Request $request,Category $category){
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success',"Category updated .");
            return $this->redirectToRoute('app_admin_category_index');
        }
        return $this->render('/admin/categories/edit.html.twig',['form' => $form->createView()]);

    }

    /**
     * @Route(path="/category/show/{id}",requirements={"id"="\d+"})
     */
    public function show(Category $category){
        return $this->render('/admin/categories/show.html.twig',compact('category'));
    }

    public function deleteForm(Category $category){
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_category_delete',array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->add('name')
            ->add('submit',SubmitType::class,array('label' => 'Delete','attr' => array('class' => 'btn btn-danger')))
            ->getForm();
        ;
        return $form;
    }

    /**
     * @Route(path="/category/delete/{id}",requirements={"id"="\d+"})
     */
    public function delete(Request $request,Category $category){
        $form = $this->createForm(DeleteCategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            $this->addFlash('success',"Category deleted");
            return $this->redirectToRoute('app_admin_category_index');
        }
        return $this->render('/admin/categories/delete.html.twig',['form' => $form->createView(),'category' => $category]);
    }
}