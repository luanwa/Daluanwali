<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	public function index(){
		$d = M("Magazineset")->select();
		$this->assign("model", $d);
		$this->assign("magazines", M("Magazine")->where(array('msid' => $d[0]['id']))->select());
		$this->display();
	}
	
	public function ms2a(){
		$ms = $_GET['ms'];
		$m = M("Magazine")->where(array('msid' => $ms))->order("time desc")->getField('id');
		$id =  M("Article")->where(array('mid' => $m))->order("sort")->getField('id');
		redirect("/read?id=" . $id);
	}
	
	public function m2a(){
		$m = $_GET['mid'];
		$id =  M("Article")->where(array('mid' => $m))->order("sort")->getField('id');
		redirect("/read?id=" . $id);
	}
	
	private function checkKey($value){
		return $value == "daluanwali";
	}
	
	public function read(){
		if ($_POST['code'] && $this->checkKey($_POST['code'])) {
			setcookie("code", $_POST['code'], time() + 3600 * 24 * 14);
			$this->show("<script>window.location.href=window.location.href;</script>");
			return;
		} elseif (!$this->checkKey($_COOKIE['code'])) {
			$this->show("<center style='margin-top:200px;'><form method='post'>邀请码：<input name='code' /><input type='submit' /></form></center>");
			return;
		}
		$id = $_GET['id'];
		$article = M("Article")->find($id);
		$this->assign("articles", M("Article")->where(array('mid' => $article['mid']))->order("sort")->select());
		$this->assign("user", M("User")->find($article['uid']));
		$this->assign("model", $article);
		$this->display();
	}
}