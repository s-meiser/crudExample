<?php

namespace App\Repository;

use App\Entity\ListOfCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method ListOfCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListOfCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListOfCategory[]    findAll()
 * @method ListOfCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListOfCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListOfCategory::class);
    }

    public function findAllCategories(){

		return $this->createQueryBuilder('cat')
			->getQuery()
			->getResult(Query::HYDRATE_ARRAY);
	}

}
