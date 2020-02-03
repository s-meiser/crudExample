<?php


namespace App\SetupWizard\Index\Repository;

use App\SetupWizard\Index\Entity\ListOfCategory;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;

class ListOfCategoryRepo extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, ListOfCategory::class);
	}

	public function getCategoryData(){
		$data = $this->createQueryBuilder('data');
		return $data
			->select('data')
			->getQuery()
			->getResult(Query::HYDRATE_ARRAY);
	}
}