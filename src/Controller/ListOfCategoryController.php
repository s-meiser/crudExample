<?php

namespace App\Controller;

use App\Entity\ListOfCategory;
use App\Form\ListOfCategoryType;
use App\Repository\ListOfCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/list/of/category")
 */
class ListOfCategoryController extends AbstractController
{
    /**
     * @Route("/", name="list_of_category_index", methods={"GET"})
     */
    public function index(ListOfCategoryRepository $listOfCategoryRepository): Response
    {
        return $this->render('list_of_category/index.html.twig', [
            'list_of_categories' => $listOfCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="list_of_category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $listOfCategory = new ListOfCategory();
        $form = $this->createForm(ListOfCategoryType::class, $listOfCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($listOfCategory);
            $entityManager->flush();

            return $this->redirectToRoute('list_of_category_index');
        }

        return $this->render('list_of_category/new.html.twig', [
            'list_of_category' => $listOfCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="list_of_category_show", methods={"GET"})
     */
    public function show(ListOfCategory $listOfCategory): Response
    {
        return $this->render('list_of_category/show.html.twig', [
            'list_of_category' => $listOfCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="list_of_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ListOfCategory $listOfCategory): Response
    {
        $form = $this->createForm(ListOfCategoryType::class, $listOfCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('list_of_category_index');
        }

        return $this->render('list_of_category/edit.html.twig', [
            'list_of_category' => $listOfCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="list_of_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ListOfCategory $listOfCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$listOfCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($listOfCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('list_of_category_index');
    }
}
