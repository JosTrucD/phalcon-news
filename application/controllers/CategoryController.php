<?php
/**
 * @author JosT <trucbvt@gmail.com>
 * @date   Jun 23 2015
 */
class CategoryController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();
		$this->tag->setTitle('Giáo xứ Trại Gạo - Danh mục');
	}
	public function indexAction()
	{
		$this->tag->setTitle('Quản lý danh mục');
		// Create category
		$params = $this->request->getPost();
		if ($this->request->isPost()) {
			if ($this->_validation($params)) {
				if ($this->_check($params['name'])) {
					$category = new Category();
					$category->name   = $params['name'];
					if (!isset($params['parent'])) {
						$category->parent = 0;
					} else {
						$category->parent = $params['parent'];
					}
					$category->menu = 2;
					$category->active = 1;
					$category->save();
					$this->flash->success('Danh mục được tạo mới thành công');
				}					
			}
		}
		$category = Category::find();
		$arr = array();
		foreach ($category as $value) {
			$array = array('id'=>$value->id,'name'=>$value->name,'parent'=>$value->parent,'menu'=>$value->menu,'active'=>$value->active);
			$arr[] = $array;
		}
		$data['category'] = $this->helper->buildArr($arr, 'name');
		$this->view->setVar('data',$data);

		// Change active,menu
		$id     = isset($params['id']) ? $params['id'] : '';
		$active = isset($params['active']) ? $params['active'] : '';
		$menu   = isset($params['menu']) ? $params['menu'] : '';

		if ($active == 1) {
			$active = 2;
		} else if ($active == 2) {
			$active = 1;
		}

		if ($menu == 1) {
			$menu = 2;
		} else if ($menu == 2) {
			$menu = 1;
		}
		if ($id != null) {
			$category         = Category::findFirst($id);
			if ($active != '') $category->active = $active;
			if ($menu != '') $category->menu = $menu;
			$category->save();
		}
		// Popup edit
		// $cate_edit = isset($params['cate_edit']) ? $params['cate_edit'] : '';
		$cate_edit = $_REQUEST['cate_edit'];
		echo $cate_edit;
		// debug($params);
		// $categoryEdit = Category::findFirst($cate_edit);
		$this->view->setVar('category', $cate_edit);
	}
	public function updateAction()
	{
		$cate_edit = isset($params['cate_edit']) ? $params['cate_edit'] : '';
		echo $cate_edit;
		die;
	}
	private function _validation($params)
	{
		$flag = true;
		if ($params['name'] == null) {
			$this->flash->error('Tên danh mục không được bỏ trống !');
			$flag = false;
		}
		if ($params['parent'] == null) {
			$this->flash->error('Bạn chưa chọn danh mục !');
			$flag = false;
		}
		return $flag;
	}
	private function _check($value)
	{
		$flag = true;
		// $category = Category::findByName($value);
		$category = Category::find(array(
			'name = :name:',
			'bind' => array( 
            	'name' => $value)
		));
		$check = count($category);
		if ($check >= 1) {
			$this->flash->error('Danh mục đã tồn tại trong hệ thống !');
			$flag = false;
		}
		return $flag;
	}
}