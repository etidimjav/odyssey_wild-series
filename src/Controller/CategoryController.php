<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/add", name="add")
    */
    public function add(Request $request) :Response
    {
        $category = new Category();

        $form = $this->createForm(
            CategoryType::class,
            $category
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Category added !');

            return $this->redirectToRoute('category_add');
        }

        return $this->render('wild/category_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}