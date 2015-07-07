<?php
/**
 * @author JosT <trucbvt@gmail.com>
 * @date   Jun 18 2015
 */
class LoginController extends ControllerBase
{	
	public function initialize()
	{
		$this->tag->setTitle('Giáo xứ Trại Gạo - Đăng Nhập');
	}
	public function indexAction()
	{
		if ($this->request->isPost()) {
			$params = $this->request->getPost();
			if ($this->_validation($params)) {
				$user = User::findFirst(array(
	                '(user = :user: OR name = :user: OR email = :user:) AND password = :password: AND active = 1',
	                'bind' => array(
	                	'user'     => $params['user'], 
	                	'password' => md5($params['password']))
            	));
            	if ($user != false) {
            		$this->_registerSession($user);
            		$this->flash->success('Xin chào '.$user->name);
            		return $this->forward('admin');
            	} else {
            		$this->flash->error('Tên hoặc mật khẩu không đúng !');
            	}
			}
		}
	}
	/**
	 * [_validation form]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function _validation($params)
	{
		$flag = true;
		if($params['user'] ==''){
			$this->flash->error('Hãy điền tên đăng nhập.');
			$flag = false;
		}
		if($params['password'] ==''){
			$this->flash->error('Hãy điền mật khẩu.');
			$flag = false;
		}
		return $flag;
	}
	/**
	 * [_registerSession description]
	 * @param  User   $user [description]
	 * @return [type]       [description]
	 */
	private function _registerSession(User $user)
	{
		$this->session->set('auth', array(
				'id'    => $user->id,
				'user'  => $user->user,
				'name'  => $user->name,
				'email' => $user->email,
				'role'  => $user->role,
				'date'  => $user->date,
				'image' => $user->image
		));
	}
    public function endAction(){
        $this->session->remove('auth');
        // $this->flash->success('Goodbye!');
        return $this->forward('');
    }
}