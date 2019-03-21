<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\UserModel;
use App\Model\GoodsModel;

class LoginController extends Controller{
    //注册
    public function registerAdd(Request $request){
        if(request()->isMethod('post')){
            $name = $request->input('name');

            $res= UserModel::where(['name'=>$name])->first();
            if($res){
                die("用户名已存在");
            }
            $email = $request->input('email');

            $res= UserModel::where(['email'=>$email])->first();
            if($res){
                die("邮箱已存在");
            }
            $pass1 = $request->input('pwd');
            $pass2 = $request->input('pwd1');
            if($pass1 !== $pass2){
                die("密码不一致");
            }

            $pass=password_hash($pass1,PASSWORD_BCRYPT);
            $data = [
                'name'  => $request->input('name'),
                'email'  => $request->input('email'),
                'pwd' =>$pass,
                'reg_time'  => time()
            ];
            $uid = UserModel::insertGetId($data);
            var_dump($uid);

            if($uid){
                setcookie('uid',$uid,time()+86400,'/','shop.com',false,true);
                header("Refresh:3;url=/goodsList");
                echo '注册成功,正在跳转';
            }else{
                echo '注册失败';
            }
        }else{
            return view('login.register');
        }


    }
    //登录
    public function login(Request $request){
        if(request()->isMethod('post')){
            $pass=$request->input('pwd');
            $where=[
                'name'=>$request->input('name'),
                'email'=>$request->input('email')
            ];
            $res=userModel::where($where)->first();
            if($res){
                if(password_verify($pass,$res->pwd)){
                    $token = substr(md5(time().mt_rand(1,99999)),10,10);
                    setcookie('uid',$res->uid,time()+86400,'');
                    setcookie('token',$token,time()+86400,'','',false,true);
                    $request->session()->put('uid',$res->uid);
                    $request->session()->put('u_token',$token);

                    header("Refresh:2;url=/login/center");
                    echo "登录成功,正在跳转";
                }else{
                    header("Refresh:2;url=/login");
                    echo "账号或密码有误";
                }
            }else{
                echo "该用户不存在";
            }
        }else{
            return view('login.login');
        }
    }
    //退出
    public function quit(){
        setcookie('uid',null);
        setcookie('token',null);
        request()->session()->pull('uid',null);
        request()->session()->pull('u_token',null);
        header('Refresh:0;url=/login');
    }
    //个人中心
    public function center(Request $request){
        if($_COOKIE['token']!=$request->session()->get('u_token')){
            die('非法请求');
        }
        if(empty($_COOKIE['uid'])){
            header('Refresh:2;url=/userlogin');
            echo '请先登录';
            exit;
        }else{
            $list=GoodsModel::all()->toArray();
            $data=[
                'list'=>$list
            ];
            return view('goods.goodsList',$data);
        }
    }
    //商品列表
    public function goodsList(){
        $list=GoodsModel::paginate(2);
        //$list=GoodsModel::all()->toArray();
        $data=[
            'list'=>$list
        ];
        return view('goods.goodsList',$data);
    }
}