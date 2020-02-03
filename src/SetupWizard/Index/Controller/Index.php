<?php


namespace App\SetupWizard\Index\Controller;


use App\SetupWizard\Index\Entity\ListOfCategory;

class Index
{

	private
		$_this,
		$request,
		$registry,
		$forceStep,
		$session,
		$entityManager;

	public function load($_this)
	{
		$this->request = $_this->request;
		$this->registry = $_this->registry;
		$this->forceStep = $_this->forceStep;
		$this->session = $_this->session;
		$this->entityManager = $_this->entityManager;
		$this->_this = $this;
		return $this->steps();
	}


	public function return($tag, $data){
		$ret = [];
		$ret['data'] = [];
		$ret['data'][$tag] = $data;

		$user = $this->_this;
		$ret['data']['user'] = $user;
		return $ret;
	}


	public function steps(){

		if (!$this->session->isStarted()) {
			$this->session->start();
			$this->session->set('step', 'category');
		}

		if ($this->session->get('category') === '' || empty($this->session->get('category'))){
			$ret = $this->show();
		}

		return $ret;

	}

	// Create / Read / Update / Delete
	public function createListOfCategory(){

	}

	public function show()
	{

		$aListOfCategory = $this->entityManager->getRepository(ListOfCategory::class);
		$aListOfCategory = $aListOfCategory->getCategoryData();
		return $this->return('listOfCategory', $aListOfCategory);
	}

/*	public function getListOfCategory(){
		$aListOfCategory = $this->entityManager->getRepository(ListOfCategory::class);
		$aListOfCategory = $aListOfCategory->getCategoryData();
		return $aListOfCategory;
	}

	public function setListOfCategory(){
		$aListOfCategory = $this->entityManager->getRepository(ListOfCategory::class);
		$aListOfCategory = $aListOfCategory->getCategoryData();
		return $aListOfCategory;
	}

	public function updateListOfCategory(){
		$aListOfCategory = $this->entityManager->getRepository(ListOfCategory::class);
		$aListOfCategory = $aListOfCategory->getCategoryData();
		return $aListOfCategory;
	}

	public function deleteListOfCategory(){
		$aListOfCategory = $this->entityManager->getRepository(ListOfCategory::class);
		$aListOfCategory = $aListOfCategory->getCategoryData();
		return $aListOfCategory;
	}*/


	public function category(){
		//Sofort;Lastschrift ect.
		#$aListOfCategory = $this->getListOfCategory();
		#var_dump($aListOfCategory);
		#$aListCurrencys = $entityManager->getRepository(ListOfCurrencys::class);
		#$aListCurrencys = $aListCurrencys->getCurrencyData();

	}


}