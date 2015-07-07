<?php
/**
 * @author JosT <trucbvt@gmail.com>
 * @date   Jun 17 2015
 */
use Phalcon\Mvc\Controller;
class ControllerHome extends Controller
{
	protected function initialize()
	{
        $this->view->setTemplateAfter('home');
		$category = Category::find();
		$arr = array();
		foreach ($category as $value) {
			$array = array('id'=>$value->id,'name'=>$value->name,'parent'=>$value->parent,'menu'=>$value->menu,'active'=>$value->active);
			$arr[] = $array;
		}
		$category = $this->helper->buildArr($arr, 'name');
		$menu     = $this->helper->filter_by_value($category, 'menu', 1);
		$this->view->setVar('menu', $menu);
	}
}