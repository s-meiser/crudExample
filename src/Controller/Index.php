<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Workflow\Registry;

use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use ProxyManager\Proxy\LazyLoadingInterface;


use ReflectionClass;
use ReflectionProperty;


class Index extends AbstractController
{

	public $forceStep;

	/**
	 * @var KernelInterface
	 */
	public $appKernel;

	/**
	 * @var Request
	 */
	public $request;

	/**
	 * @var Session
	 */
	public $session;

	/**
	 * @var Registry
	 */
	public $registry;

	public $entityManager;

	/**
	 * @var Response
	 */
	private $response;


	public function __construct(KernelInterface $appKernel)
	{
		$this->appKernel = $appKernel;
		$this->response = New Response();
	}

	public function accessProperty($obj) {
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

	public function getTemplatePath(){

		$twigPath = $this->get('twig')->getLoader();

		$pattern = $this->appKernel->getProjectDir();

		$pattern = preg_replace('/\\\\/', '\\\\\\\\', $pattern);

		$twigPath = $this->accessProperty($twigPath);
		$twigPath = $twigPath['paths']['__main__'][0];

		$path = preg_replace('/'.$pattern.'/', '', $twigPath);

		$path = $this->appKernel->getProjectDir().''.$path.'/';

		return $path;
	}


	public function fileExist($str){
		if (file_exists($this->getTemplatePath().$str)){
			return true;
		}
		return false;
	}

	public function getRoutes(){
		$fileLocator = new FileLocator([
			$this->appKernel->getProjectDir().'/config'
		]);
		$loader = new YamlFileLoader($fileLocator);
		$routes = $loader->load('routes.yaml');
		$aRoutes = $this->accessProperty($routes);
		return $aRoutes['routes'];
	}

	public function getRoutesByControllerName($controllerName){
		$routes = $this->getRoutes();
		foreach ($routes as $key => $value) {
			if ($key === $controllerName){

				$defaults = $this->accessProperty($value);
				return $defaults['defaults']['_controller'];

			}
		}

	}

	public function regServices(){

		$request = $this->request;
		$registry = $this->registry;

		$containerBuilder = new ContainerBuilder();
		$containerBuilder->addObjectResource($request);
		$containerBuilder->addObjectResource($registry);
		$containerBuilder->compile();

		return $containerBuilder;

	}

	public function loadClass($namespace, $controllerName){
		// init service container
		$containerBuilder = new ContainerBuilder();
		// add service into the service container
		$containerBuilder->register($controllerName.'.service', $namespace);
		$containerBuilder->addObjectResource($this->request);
		// fetch service from the service container

		try {
			return $containerBuilder->get($controllerName . '.service');
		} catch (\Exception $e) {
			return $e;
		}
	}

	public function getCurrentStep(){
		if (empty($this->session->get('step'))) {
			return false;
		} else {
			return $this->session->get('step');
		}
	}

	/**
	 * @Route("/", methods={"GET"})
	 */
	public function index(
		Request $request,
		Registry $registry,
		SessionInterface $session,
		$forceStep = 0
	)
	{

		$this->request = $request;
		$this->registry = $registry;
		$this->forceStep = $forceStep;
		$this->session = $session;
		$this->entityManager = $this->getDoctrine();

		$section = $this->accessProperty($request->query);



		if (!isset($section['parameters']['section']) || empty($section['parameters']['section'])){
			// lade Index
			$namespace = $this->getRoutesByControllerName(ucfirst('Index'));
			$service = $this->loadClass($namespace, 'Index');

			$ret = $service->load($this);

			if ($this->getCurrentStep()){

				$step = $this->getCurrentStep().'\\';
				$content = $this->renderView($step.'show.html.twig', [
					'data' => $ret
				]);

			} else {
				$content = $this->renderView('start.html.twig', [
					'data' => $ret
				]);
			}


		} else {

			// lade Unterseite eg. index.php?section=Sites << Sites
			$fileName = $section['parameters']['section'].'.twig';
			$controllerName = $section['parameters']['section'];
			$namespace = $this->getRoutesByControllerName(ucfirst($controllerName));

			$service = $this->loadClass($namespace, $controllerName);

			if ($this->fileExist($fileName)){
				$content = $this->renderView($fileName, [
					'data' => $service->load()
				]);
			} else {
				$content = $this->renderView('error.twig', [
					'data' => 'Template Datei nicht vorhanden'
				]);
			}

		}

		$this->response->setContent($content);

		return $this->response;

	}




}