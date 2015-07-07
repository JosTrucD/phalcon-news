<?php
/**
 * @author JosT <trucbvt@gmail.com>
 * @date   Jul 7 2015
 */
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
class CommentController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();
	}
	public function createAction()
	{
		$this->view->disable();
	}
}