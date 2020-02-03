<?php


namespace App\SetupWizard\Index\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="App\SetupWizard\Index\Repository\ListOfCategoryRepo")
 */
class ListOfCategory
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="UUID")
	 * @ORM\Column(type="string")
	 */
	private $uuid;

	/**
	 * @ORM\Column(type="string")
	 */
	private $name;


}