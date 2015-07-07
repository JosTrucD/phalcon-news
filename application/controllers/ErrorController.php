<?php
/**
 * @author JosT <trucbvt@gmail.com>
 * @date   Jul 4 2015
 */
use Phalcon\Mvc\Controller;
class ErrorController extends Controller
{
	public function initialize()
	{
		$this->tag->setTitle('Error - Giáo xứ Trại Gạo');
		$this->view->setTemplateAfter('error');
	}
	public function indexAction()
	{
		$this->tag->setTitle('404 Error - Giáo xứ Trại Gạo');
	}
}