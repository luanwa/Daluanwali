<?php
class IndexAction extends Action {
	
	public function index(){
		$action = $_GET['action'];
		if(!$action)
			redirect('index.html');
		echo $this->$action();
		return;
	}
	
	public function login() {
		$username = $_GET['username'];
		$password = $_GET['password'];
		$dbUser = M("User")->where(array('username' => $username))->find();
		if ($dbUser === null) {
			return $this->fail('用户名不存在');
		} elseif ($dbUser['password'] !== sha1($password)) {
			return $this->fail('密码不正确');
		} else {
			session('user', $dbUser);
			return $this->succ('登录成功');
		}
	}
	
	public function logout() {
		session('user', null);
		return $this->succ('登出成功');
	}
	
	public function getuser() {
		$dbUser = session('user');
		if ($dbUser === null) {
			return $this->fail('未登录');
		}
		return $this->succ($dbUser);
	}
	
	public function register() {
		$username = $_GET['username'];
		$password = $_GET['password'];
		if (!$username) {
			return $this->fail('用户名不能为空');
		}
		if (!$password) {
			return $this->fail('密码不能为空');
		}
		if (M("User")->where(array('username' => $username))->count() > 0) {
			return $this->fail('用户名已存在');
		} elseif (M("User")->add(array('username' => $username, 'password' => sha1($password)))) {
			$this->login();
			return $this->succ('注册成功');
		} else {
			return $this->fail('未知错误');
		}
	}
	
	public function updateuser() {
		$update = array();
		$update['nickname'] = $_GET['nickname'];
		$update['gender'] = $_GET['gender'] == 'male';
		$update['introduce'] = $_GET['introduce'];
		$password = $_GET['password'];
		
		$dbUser = session('user');
		if ($dbUser === null) {
			return $this->fail('未登录');
		} elseif ($dbUser['nickname'] && sha1($password) != $dbUser['password']) {
			return $this->fail('已经填写过资料了');
		} elseif (M('User')->where(array('id' => $dbUser['id']))->save($update)) {
			session('user', M('User')->find($dbUser['id']));
			return $this->succ('资料更新成功');
		} else {
			return $this->fail('未知错误');
		}
	}
	
	private function fail($msg) {
		return json_encode(array('success' => false, 'message' => $msg));
	}
	
	private function succ($msg) {
		return json_encode(array('success' => true, 'message' => $msg));
	}
}