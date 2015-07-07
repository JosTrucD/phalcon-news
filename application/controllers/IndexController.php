<?php 
/**
 * @author JosT <trucbvt@gmail.com>
 * @date   Jun 17 2015
 */
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
class IndexController extends ControllerHome
{
	public function initialize()
	{
		parent::initialize();
		$this->tag->setTitle('Giáo xứ Trại Gạo - Cổng thông tin điện tử');
	}
	public function indexAction()
	{
		$data['slider'] = News::find(array(
            'active = 1',
			'order' => 'id DESC',
			'limit' => 6
		));
		$data['category'] = Category::find(array('active = 1'));
		$this->view->setVar('data', $data);
	}
	public function detailAction()
	{
		$params = $this->request->getQuery();
		if ($params['_url'] != null)
		$string = ltrim($params['_url'], '/bai-viet');
		$array  = explode('-', $string);
		$id     = $array[0];
		if ($id != '' && $id > 0) {	
			$news = News::findFirstById($id);
			$data['relate'] = News::find(array(
	            'id !='.$id.' AND active = 1 AND category ='.$news->category,
				'order' => 'id DESC',
				'limit' => 8
			));
			$this->tag->setTitle($news->name);

			debug($params);die;

			$this->view->setVar('news', $news);
			$this->view->setVar('data', $data);
		} else {
			$this->response->redirect('error');
			// $this->dispatcher->forward(array('controller' => 'error', 'action' => 'index'));
		}
	}
	public function listAction()
	{
		$params = $this->request->getQuery();
		if ($params['_url'] != null)
		$string = ltrim($params['_url'], '/danh-muc');
		$array  = explode('-', $string);
		$id     = $array[0];
		// Get name category
		$category = Category::findFirstById($id);
		$this->tag->setTitle($category->name);
		// Get list category of id
		if ($id != '' && $id > 0) {	
			// The data set to paginate
			$news = News::find(array(
	            'active = 1 AND category ='.$id,
				'order' => 'id DESC'
			));
			$currentPage = (int) $_GET["page"];
			// Create a Model paginator, show 10 rows by page starting from $currentPage
			$paginator   = new PaginatorModel(
			    array(
			        "data"  => $news,
			        "limit" => 13,
			        "page"  => $currentPage
			    )
			);
			// Get the paginated results
			$page = $paginator->getPaginate();
			$this->view->setVar('page', $page);
			$this->view->setVar('category', $category);
		} else {
			$this->response->redirect('error');
		}
	}
}