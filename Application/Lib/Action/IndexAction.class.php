<?php
class IndexAction extends Action {
	/* 基础入口 */
	public function index(){
		$action = strval($_REQUEST['action']);
		if(!$action)
			redirect('index.html');
		echo $this->$action();
		return;
	}	
	private function fail($msg) {
		return json_encode(array('success' => false, 'message' => $msg));
	}	
	private function succ($msg) {
		return json_encode(array('success' => true, 'message' => $msg));
	}
		
	/* 用户模块 */
	public function login() {
		$username = strval($_REQUEST['username']);
		$password = strval($_REQUEST['password']);
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
		$username = strval($_REQUEST['username']);
		$password = sha1(strval($_REQUEST['password']));
		$invite = strval($_REQUEST['invite']);
		if (!$username) {
			return $this->fail('用户名不能为空');
		}
		if (!$password) {
			return $this->fail('密码不能为空');
		}
		if (!$invite) {
			return $this->fail('邀请码不能为空');
		}
		if (M("User")->where(array('username' => $username))->count() > 0) {
			return $this->fail('用户名已存在');
		} elseif (!M("Invitecode")->where(array('code' => $invite, 'used' => 0))
			->save(array('used' => 1, 'usedtime' => array('exp', 'CURRENT_TIMESTAMP'), 'useduser' => $username))) {
			return $this->fail('邀请码不正确');
		} elseif (M("User")->add(array('username' => $username, 'password' => $password))) {
			$this->login();
			return $this->succ('注册成功');
		} else {
			return $this->fail('未知错误');
		}
	}
	public function updateuser() {
		$update = array();
		$update['nickname'] = strval($_REQUEST['nickname']);
		$update['gender'] = strval($_REQUEST['gender']) == 'male';
		$update['introduce'] = strval($_REQUEST['introduce']);
		$password = strval($_REQUEST['password']);
		
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
	
	/* 杂志文章模块 */
	function getarticle() {
		$id = strval($_REQUEST['id']);
		$article = M("Article")->find($id);
		$article['user'] = M("User")->field('nickname,image,introduce,ismale')->find($article['uid']);
		$article['articles'] = M("Article")->field('title,author')->where(array('mid' => $article['mid']))->order("sort")->select();
		return $this->succ($article);
	}
	function getmagazinesets() {
		$magazineSet = M("Magazineset")->select();
		for ($i = 0; $i < count($magazineSet); $i++) {
			$magazineSet[$i]['magazineid'] = M("Magazine")->where(array('msid' => $magazineSet[$i]['id']))->getField('id');
			$magazineSet[$i]['articleid'] = M("Article")->where(array('mid' => $magazineSet[$i]['magazineid']))->order('sort')->getField('id');
		}
		return $this->succ($magazineSet);
	}
	function getmagazines() {
		$id = strval($_REQUEST['id']);
		$magazines = M("Magazine")->where(array('msid' => $id))->select();
		for ($i = 0; $i < count($magazines); $i++) {
			$magazines[$i]['articleid'] = M("Article")->where(array('mid' => $magazines[$i]['id']))->order('sort')->getField('id');
		}
		return $this->succ($magazines);
	}
	
	/* 评论模块 */
	function getcomments() {
		$id = strval($_REQUEST['id']);
		$comments = M('Comment')->where(array('aid' => $id))->order('time')->select();
		for ($i = 0; $i < count($comments); $i++) {
			$comments[$i]['user'] = M("User")->field('nickname,image,introduce,ismale')->find($comments[$i]['uid']);
		}
		return $this->succ($comments);
	}
	function addcomment() {
		$id = strval($_REQUEST['id']);
		$content = strval($_REQUEST['content']);
		$reply = strval($_REQUEST['reply']);
		$dbUser = session("user");
		if (!$dbUser)
			return $this->fail("未登录");
		if (M("Comment")->add(array('uid' => $dbUser['id'], 'aid' => $id, 'cid' => $reply == "" ? null : $reply, 'content' => $content)))
			return $this->succ("评论成功");
		return $this->fail("未知错误");
	}
	function like() {
		$id = strval($_REQUEST['id']);
		$dbUser = session("user");
		if (!$dbUser)
			return $this->fail("未登录");
		if (M("like")->where(array('aid' => $id, 'uid' => $dbUser['id']))->count() > 0)
			return $this->fail("已经喜欢过了");
		if (M("like")->add(array('aid' => $id, 'uid' => $dbUser['id']))) {
			M("Article")->where(array('id' => $id))->setInc("likecount");
			return $this->succ("操作成功");
		}
		return $this->fail("未知错误");
	}
	
	/* 他人操作历史 */
	function getarticles() {
		$id = strval($_REQUEST['id']);
		$articles = M("Article")->field('title,time,mid')->where(array('uid' => $id))->select();
		return $this->succ($articles);
	}
	function getlike() {
		$id = strval($_REQUEST['id']);
		return $this->succ(M("Like")->field('aid,time')->where(array('uid' => $id))->select());
	}
}