<?php
/**
 * @author JosT <trucbvt@gmail.com>
 * @date   Jun 28 2015
 */
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
class NewsController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();
		$this->tag->setTitle('Giáo xứ Trại Gạo - Bài viết');
	}

	public function indexAction()
	{
		$this->tag->setTitle('Quản lý bài viết');
		$news = News::find(array('order'=>'id DESC'));
		$currentPage = ($this->request->getQuery("page","int")>0) ? $this->request->getQuery("page","int") : 1;
		// Create a Model paginator, show 10 rows by page starting from $currentPage
		$paginator   = new PaginatorModel(
		    array(
		        "data"  => $news,
		        "limit" => 10,
		        "page"  => $currentPage
		    )
		);
		// Get the paginated results
		$page = $paginator->getPaginate();
		$this->view->setVar('page', $page);		
		// Change active
		$params = $this->request->getPost();
		$id     = isset($params['id']) ? $params['id'] : '';
		$active = isset($params['active']) ? $params['active'] : '';
		if ($active == 1) {
			$active = 2;
		} else if ($active == 2) {
			$active = 1;
		}
		if ($id != null) {
			$news         = News::findFirst($id);
			$news->active = $active;
			$news->save();
		}
	}

	public function createAction()
	{
		$this->tag->setTitle('Tạo mới bài viết');
		// Danh mục
		$category = Category::find();
		$arr = array();
		foreach ($category as $value) {
			$array = array('id'=>$value->id,'name'=>$value->name,'parent'=>$value->parent,'active'=>$value->active);
			$arr[] = $array;
		}
		$data['category'] = $this->helper->buildArr($arr, 'name');
		$this->view->setVar('data',$data);

		$params = $this->request->getPost();
		if ($this->request->isPost()) {
			if ($this->_validation($params)) {
				if ($this->_check($params['name'])) {
					$news = new News();
					$image = $_FILES['image']['name'];
					if ($image != null) {
						$news->image = $image;
						$folder = date('m-d-Y');
						$this->upload->file_image('uploads/news/', $folder);
					}
					$news->name     = trim($params['name']);
					$news->tag      = trim($params['tag']);
					$news->category = $params['category'];
					$news->info     = $params['info'];
					$news->date     = date('Y-m-d H:i:s', time());
					$news->author   = $this->_user['user'];
					$news->active   = 1;
					$news->save();
					$this->flash->success('Bài viết được tạo mới thành công');
					$this->forward('news');
				}
			}
		}

	}

	public function editAction()
	{
		$this->tag->setTitle('Cập nhật bài viết');
		$id   = $this->request->get('i', 'int') > 0 ? $this->request->get('i', 'int') : '';
		if ($id == '') $this->forward('news');
		$news = News::findFirstById($id);
		$this->view->setVar('news', $news);
		// Danh mục
		$category = Category::find();
		$arr = array();
		foreach ($category as $value) {
			$array = array('id'=>$value->id,'name'=>$value->name,'parent'=>$value->parent,'active'=>$value->active);
			$arr[] = $array;
		}
		$data['category'] = $this->helper->buildArr($arr, 'name');
		$this->view->setVar('data',$data);
		// Update
		$params = $this->request->getPost();
		if ($this->request->isPost()) {
			if ($this->_validation($params)) {
				$image = $_FILES['image']['name'];
				if ($image != null) {
					$folder = date('m-d-Y',strtotime($news->date));
					$news->image = $image;
					$this->upload->file_image('uploads/news/', $folder);
				}
				$news->name     = trim($params['name']);
				$news->tag      = trim($params['tag']);
				$news->category = $params['category'];
				$news->info     = $params['info'];
				$news->save();
				$this->flash->success('Bài viết được cập nhật thành công');
				$this->forward('news');
			}
		}
	}

	private function _validation($params)
	{
		$flag = true;
		if ($params['name'] == null) {
			$this->flash->error('Tên bài viết không được bỏ trống !');
			$flag = false;
		}
		if ($params['category'] == '') {
			$this->flash->error('Chưa chọn danh mục cho bài viết !');
			$flag = false;
		}
		if ($params['info'] == null) {
			$this->flash->error('Bài viết chưa có nội dung !');
			$flag = false;
		}
		return $flag;
	}

	private function _check($value)
	{
		$flag = true;
		$news = News::find(array(
			'name = :name:',
			'bind' => array( 
            	'name' => $value)
		));
		$check = count($news);
		if ($check >= 1) {
			$this->flash->error('Bài viết đã tồn tại trong hệ thống !');
			$flag = false;
		}
		return $flag;
	}
}