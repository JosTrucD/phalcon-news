<?php
/**
 * @author JosT <trucbvt@gmail.com>
 * @date   Jun 17 2015
 */
use Phalcon\Mvc\Controller;
class ControllerBase extends Controller
{
	protected $_user = array();
    protected function initialize()
    {
		$this->_user = $this->session->get('auth');
        if ($this->_user == false) {
            return $this->forward('login');
        }
        $this->view->setTemplateAfter('main');

        $date['fromdate'] = date('Y-m-d', strtotime ( '-7 day' , strtotime (date('Y-m-d'))));
        $date['todate']   = date('Y-m-d');
        $this->view->setVar('date', $date);
        $this->view->setVar('_user', $this->_user);
    }
    protected function forward($uri){
        return $this->response->redirect($uri);
    }	
}