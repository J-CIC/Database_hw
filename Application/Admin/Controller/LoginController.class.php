<?php
/*
* @Author:            CIC
* @LastModify:     2017-01-11 17:19:34
* @Description:    LoginController
*/
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;

class LoginController extends Controller
{
	// public function index()
	// {
	// 	print_r($_SESSION);
	// 	print_r(Rbac::getAccessList(session(C('USER_AUTH_KEY'))));
	// 	if (session(C('USER_AUTH_KEY'))){
	// 		$this->show("<h1>Success Login</h1>");
	// 		$this->display();
	// 	}
	// 	else{
	// 		$this->show("<h1>Didn't Login</h1>");
	// 		$this->display();
	// 	}
	// 	// $this->display('Login_index');
	// }
	public function index()
	{
		if(IS_POST){
			$username = I('post.username', Null, 'htmlspecialchars');
			$password = I('post.password', Null, 'htmlspecialchars');
			if(!($username && $password))
			{
				$login['error'] = 1;
				$login['info'] = "账号或密码不能为空";
				$this->assign("login",$login);
				$this->display("index");
				exit();
			}
			$map['username'] = $username;
			$map['status'] = array('gt',0);
			$authInfo = RBAC::authenticate($map);
			if(empty($authInfo))
			{
				$login['error'] = 1;
				$login['info'] = "账号不存在或已失效";
				$this->assign("login",$login);
				$this->display("index");
				exit();
			}else{
				if($authInfo['password'] != md5($password)){
					$login['error'] = 1;
					$login['info'] = "账号或密码错误";
					$this->assign("login",$login);
					$this->display("index");
					exit();
				}else{
					$_SESSION[C('USER_AUTH_KEY')] = $authInfo['id'];
					$_SESSION['nickname'] = $authInfo['nickname'];
					$_SESSION['username'] = $authInfo['username'];
					$_SESSION['logintime'] = $authInfo['logintime'];
					$_SESSION['loginip'] = $authInfo['loginip'];
					//getLocation
					$con['user_id'] = $authInfo['id'];
					session("role_id", M("think_role_user")->where($con)->field("role_id")->find()['role_id']);
					$ip = $_SERVER['REMOTE_ADDR'];
					$User = D("User"); // 实例化User对象
					$update['id'] = $authInfo['id'];
					$update['loginTime'] = date("Y-m-d H:i:s" ,time());
					$update['loginIp'] = $ip;
					if ($User->update_info($update)===false){
						$login['error'] = 1;
						$login['info'] = "系统故障";
						$this->assign("login",$login);
						$this->display("index");
						exit();
					}else{
						// 验证通过 可以进行其他数据操作
						$this->redirect("Index/index");
					}
				}
			}
		}else if(IS_GET){
			$this->display("index");
		}
	}
	public function editUser(){
		$this->user_id=session("authId");
		$condition["id"] = $this->user_id;
		$this->assign("USERINFO",D("User")->field("nickname,loginTime,loginIp")->where($condition)->find());
		$id = session("authId");
		$database = D("Menu");
		$menu = $database->getMenuList(session("role_id"));
		$this->assign("menu",$menu);
		if(IS_POST){
			$condition["id"] = $id;
			$data = I("post.");
			$data['password'] = md5($data['password']);
			if(D("User")->where($condition)->save($data)){
				$this->success("编辑成功","index");
			}else{
				$this->error("编辑失败");
			}
		}else{
			$condition["id"] = $id;
			$field = array("password");//排除字段
			$this->assign("user",D("User")->field($field,true)->where($condition)->find());
			$this->display();
		}
	}
	// public function login()
	// {
	// 	$username = I('post.username', Null, 'htmlspecialchars');
	// 	$password = I('post.password', Null, 'htmlspecialchars');
	// 	if(!($username && $password))
	// 	{
	// 		// $this->error('帐号错误！');
	// 		$ajax['status'] = 0;
	// 		$ajax['info'] = "账号错误";
	// 		$this->ajaxReturn($ajax);
	// 		exit();
	// 	}
	// 	$map['username'] = $username;
	// 	$map['status'] = array('gt',0);
	// 	$authInfo = RBAC::authenticate($map);
	// 	if(empty($authInfo))
	// 	{
	// 		$ajax['status'] = 0;
	// 		$ajax['info'] = "账号不存在或错误";
	// 		$this->ajaxReturn($ajax);
	// 		exit();
	// 	}else{
	// 		if($authInfo['password'] != ($password)){
	// 			$ajax['status'] = 0;
	// 			$ajax['info'] = "密码错误";
	// 			$this->ajaxReturn($ajax);
	// 			exit();
	// 		}else{
	// 			$_SESSION[C('USER_AUTH_KEY')] = $authInfo['id'];
	// 			$_SESSION['nickname'] = $authInfo['nickname'];
	// 			$_SESSION['username'] = $authInfo['username'];
	// 			$_SESSION['loginTime'] = $authInfo['loginTime'];
	// 			$_SESSION['loginIp'] = $authInfo['loginIp'];
	// 			//getLocation
	// 			$con['user_id'] = $authInfo['id'];
	// 			session("role_id", M("think_role_user")->where($con)->field("role_id")->find()['role_id']);
	// 			$ip = $_SERVER['REMOTE_ADDR'];
	// 			$User = D("User"); // 实例化User对象
	// 			$update['id'] = $authInfo['id'];
	// 			$update['loginTime'] = date("Y-m-d H:i:s" ,time());
	// 			$update['loginIp'] = $ip;
	// 			if ($User->update_info($update)===false){
	// 				$ajax['status'] = 1;
	// 				$ajax['info'] = "更新数据出错";
	// 				$this->ajaxReturn($ajax);
	// 				exit();
	// 			}else{
	// 				// 验证通过 可以进行其他数据操作
	// 				$User->add();
	// 				$ajax['status'] = 1;
	// 				$ajax['info'] = "登录成功";
	// 				$this->ajaxReturn($ajax);
	// 				exit();
	// 			}
	// 		}
	// 	}
	// }
	public function logout(){
		if(!session('?USER_AUTH_KEY')){
			session(null);
			redirect(__ROOT__."/Admin/Login");
		}
	}
}