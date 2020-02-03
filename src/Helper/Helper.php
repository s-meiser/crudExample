<?php


namespace App\Helper;

use ReflectionClass;
use ReflectionProperty;

class Helper
{
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
}