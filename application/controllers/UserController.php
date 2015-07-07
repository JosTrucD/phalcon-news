<?php
/**
 * @author JosT <trucbvt@gmail.com>
 * @date   Jun 18 2015
 */
class UserController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();
		$this->tag->setTitle('Giáo xứ Trại Gạo - Người dùng');
	}
	public function indexAction()
	{
		$this->tag->setTitle('Quản lý người dùng');
		$data['users']   = User::find(array('order'=> 'user'));
		$this->view->setVar('data', $data);
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
			$user       = User::findFirst($id);
			$user->active = $active;
			$user->save();
		}
	}
	public function createAction()
	{
		$this->tag->setTitle('Tạo mới Người dùng');
		if ($this->request->isPost()) {
			$params = $this->request->getPost();
			if ($this->_validation($params)) {
				if ($this->_check($params['user'])) {
					$user = new User();
					$image = $_FILES['image']['name'];
					if ($image != null) {
						$user->image = $image;
						$folder = date('m-d-Y');
						$this->upload->file_image('uploads/users/', $folder);
					}
					$user->user      = $params['user'];
					$user->name      = $params['name'];
					$user->email     = $params['email'];
					$user->role      = $params['role'];
					$user->active    = 1;
					$user->date      = date('Y-m-d H:i:s', time());
					$user->create_by = $this->_user['user'];
					if ($params['password'] != $params['password_re']) {
						$this->flash->error('Mật khẩu không khớp nhau. Vui lòng nhập lại !');
					} else {					
						$user->password  = md5($params['password']);
			 			$user->save(); 	
						$this->flash->success('User được tạo mới thành công');
						$this->forward('user');
					}
				}
			}			
		}
	}
	public function editAction()
	{
		$this->tag->setTitle('Cập nhật Người dùng');
		$id   = $this->request->get('i', 'int') > 0 ? $this->request->get('i', 'int') : '';
		if ($id == '') $this->forward('user');
		$user = User::findFirstById($id);
		$this->view->setVar('user', $user);
		if ($this->request->isPost()) {
			$params = $this->request->getPost();
			if ($this->_validation($params)) {
				$image = $_FILES['image']['name'];
				if ($image != null) {
					$folder = date('m-d-Y',strtotime($user->date));
					$this->upload->delete_file('uploads/users/'.$folder, $user->image);
					$user->image = $image;
					$this->upload->file_image('uploads/users/', $folder);
				}
				$user->user      = $params['user'];
				$user->name      = $params['name'];
				$user->email     = $params['email'];
				$user->role      = $params['role'];
				$user->create_by = $this->_user['user'];
				$user->active    = 1;
				if ($params['password_new'] != $params['password_re']) {
					$this->flash->error('Mật khẩu không khớp nhau. Vui lòng nhập lại !');						
				} else {
					$user->password = md5($params['password_new']);
					$user->save();
					$this->flash->success('User được cập nhật thành công');
					$this->forward('user');
				}
			}
		}
	}
	/**
	 * [_validation description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function _validation($params)
	{
		$flag = true;
		if (isset($params['user']) && $params['user'] == null) {
			$this->flash->error('User không được bỏ trống !');
			$flag = false;
		}
		if (@isset($params['password']) && $params['password'] == null) {
			$this->flash->error('Passwrod không được bỏ trống !');
			$flag = false;
		}
		if (isset($params['email']) && $params['email'] == null) {
			$this->flash->error('Email không được bỏ trống !');
			$flag = false;
		} else if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
			$this->flash->error('Email không hợp lệ');
			$flag = false;
		}
		return $flag;
	}
	private function _check($value)
	{
		$flag = true;
		$user = User::find(array(
			'user = :user: OR email = :user:',
			'bind' => array( 
            	'user' => $value)
		));
		$check = count($user);
		if ($check >= 1) {
			$this->flash->error('User đã tồn tại trong hệ thống !');
			$flag = false;
		}
		return $flag;
	}
}
