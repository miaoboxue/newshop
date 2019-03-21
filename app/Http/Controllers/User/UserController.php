<?php

namespace App\Http\Controllers\User;

use App\Model\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\UserModel;

class UserController extends Controller
{
    //
	//eeeeexxxxxxaaaaaooooooo
	public function user($uid)
	{
		echo $uid;
	}

	public function test()
    {
        echo '<pre>';print_r($_GET);echo '</pre>';
    }

	public function add()
	{
		$data = [
			'name'      => str_random(5),
			'age'       => mt_rand(20,99),
			'email'     => str_random(6) . '@gmail.com',
			'reg_time'  => time()
		];

		$id = UserModel::insertGetId($data);
		var_dump($id);
	}

	/** 注册*/
	public function reg(){
		return view('users.reg');
	}
	public function doReg(Request $request){
		/*echo __METHOD__;
		echo '<pre>';print_r($_POST);echo '</pre>';*/
		//exit;
		$name = $request->input('name');
		$new_name = UserModel::where(['name'=>$name])->first();
//		dump($new_name);exit;
		if($new_name){
			exit('此用户名已存在');
		}

		$pwd = $request->input('pwd');
		$pwd1 = $request->input('pwd1');
		if($pwd !== $pwd1 ){
			exit('确认密码与密码保持一致');
		}
		$pwd = password_hash($request->input('pwd'),PASSWORD_BCRYPT);
		/*echo $pwd;echo '<br/>';
		$res = password_verify($request->input('pwd'),'$2y$10$/8FuGHIhkIwi353vl0mBFOkn5AfrR03gzqwqwd8gnTcNsRcITU/QO');
		var_dump($res);exit;*/
		$data = [
			'name' => $request->input('name'),
			'pwd' => $pwd,
			'age' => $request->input('age'),
			'email' => $request->input('email')
		];
		$id = UserModel::insertGetId($data);
		//var_dump($id);
		if($id){
			setcookie('id',$id,time()+86400,'/','larvel.com',false,true);//名，值，过期时间，路径，域名，secure，httponly(默认安全true)
			$token = substr(md5(time().mt_rand(1,99999)),10,10);
			$request->session()->put('u_token',$token);
			$request->session()->put('uid',$id);

			$request->session()->put('name',$id['name']);

			echo 'successly';
			header("refresh:1;'/cart'");
		}else{
			echo 'fail';
		}
	}

    /** 登录*/
    public function login(){
        return view('users.login');
    }
	public function doLogin(Request $request){
		//echo __METHOD__;
        $name = $request->input('name');
        $where = [
            'name' => $name,
        ];
		$res = UserModel::where($where)->first();

		if($res){
			if(password_verify($request->input('pwd'),$res['pwd'])){
				$token = substr(md5(time().mt_rand(1,99999)),10,10);
				setcookie('id',$res['id'],time()+86400,'/','larvel.com',false,true);
				setcookie('token',$token,time()+86400,'/','',false,true);

				$request->session()->put('u_token',$token);
				$request->session()->put('uid',$res['id']);

				echo 'successly';
				header("refresh:1,url='/cart'");
			}else{
				exit('密码错误');
			}
		}else{
			exit('此用户不存在');
		}
	}

	public function center(Request $request){
		if(!empty($_COOKIE['token'])){
			if($_COOKIE['token']!=$request->session()->get('u_token')){
				exit("非法请求");
			}else{
				echo '正常请求';
			}
		}

//		echo 'u_token: '.$request->session()->get('u_token'); echo '</br>';
		if(empty($_COOKIE['id'])){
			echo '请先登录';
			header("refresh:2,url='/userlogin'");exit;
		}else{
			$where = [
				'id' => $_COOKIE['id'],
			];
		//var_dump($where);exit;
			UserModel::where($where)->first();
			//print_r($res);exit;
			echo 'ID:'.$_COOKIE['id'].'欢迎回来';
		}
	}
	/**	退出*/
	public function quit(){
		session()->pull('u_token',null);
		header("refresh:0.2;url='/userlogin'");
	}
}
