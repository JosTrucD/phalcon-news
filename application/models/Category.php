<?php
/**
* @author JosT <trucbvt@gmail.com>
* @date   Jun 24 2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Category extends Common
{
	public $id;
	public $name;
	public $parent;
	public $menu;
	public $active;
	public static function getName($id)
	{
		$sql = 'SELECT id, name FROM category WHERE id = '.$id;
		$category = new Category();
		return new Resultset(null, $category, $category->getReadConnection()->query($sql, $id));
	}
}