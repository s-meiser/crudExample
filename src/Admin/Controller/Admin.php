<?php


namespace App\Admin\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use ReflectionClass;
use ReflectionProperty;

class Admin extends AbstractController
{

	/**
	 * @var Response
	 */
	private $response;


	public function __construct()
	{
		$this->response = New Response();
	}

	public function accessProtected($obj) {
		$reflection = new ReflectionClass($obj);
		$props = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);

		$access = [];
		foreach ($props as $key => $value) {
			$property = $reflection->getProperty($value->name);
			$property->setAccessible(true);
			$access[$value->name] = $property->getValue($obj);
		}
		return $access;
	}

	/**
	 * @Route("/admin", name="app_admin")
	 */
	public function index()
	{
		$hasAccess = $this->isGranted('ROLE_ADMIN');
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$user = $this->getUser();
		$user = $this->accessProtected($user);
		$user = $user['username'];
		$content = $this->renderView('admin/admin.html.twig', [
			'data' => 'asdasd',
			'user' => $user
		]);

		$this->response->setContent($content);

		return $this->response;
	}
}