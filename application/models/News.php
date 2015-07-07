<?php
/**
* @author JosT <trucbvt@gmail.com>
* @date   Jun 29 2015
*/
class News extends Common
{
	public $id;
	public $name;
	public $info;
	public $tag;
	public $date;
	public $author;
	public $category;
	public $active;
	public $image;
	// public function initialize()
	// {
	// 	$this->hasOne('category', 'Category', 'id', array(
	// 		'reusable' => true
	// 	));
	// }
}