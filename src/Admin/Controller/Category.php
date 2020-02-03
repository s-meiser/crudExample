<?php


namespace App\Admin\Controller;

use App\Entity\ListOfCategory;
use App\Form\ListOfCategoryType;
use App\Repository\ListOfCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Helper\Helper;

class Category extends AbstractController
{
	private $accessProtected;
	private $response;

	public function __construct()
	{
		$this->accessProtected = new Helper();
		$this->response = New Response();
	}

	/**
	 * @Route("/admin/category", name="app_category", methods={"GET","POST"})
	 */
	public function index(ListOfCategoryRepository $listOfCategoryRepository, Request $request)
	{

		#$hasAccess = $this->isGranted('ROLE_ADMIN');
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$listOfCategory = new ListOfCategory();
		$form = $this->createForm(ListOfCategoryType::class, $listOfCategory);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($listOfCategory);
			$entityManager->flush();
		}

		$content = $this->renderView('admin/category.html.twig', [
			'data' => $listOfCategoryRepository->findAllCategories(),
			'form' => $form->createView()
		]);



		$this->response->setContent($content);
		return $this->response;
	}

	/**
	 * @Route("/admin/category/{id}", name="app_category_detail", methods={"GET"})
	 */
	public function show(ListOfCategory $listOfCategory): Response
	{

		return $this->render('admin/detailCategory.html.twig', [
			'list_of_category' => $listOfCategory,
		]);
	}


	/**
	 * @Route("/admin/category/{id}/edit", name="list_of_category_edit", methods={"GET","POST"})
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
}