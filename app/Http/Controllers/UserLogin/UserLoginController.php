<?php

namespace App\Http\Controllers\UserLogin;
use App\Model\LoginModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
class UserLoginController extends Controller
{
    //
    //登录
    public function userLogin(Request $request){
        $email = $request -> input('email');
        $password = $request -> input('password');

        $userInfo=LoginModel::where(['email'=>$email])->first();
        if(empty($userInfo)){
            $response=[
                'errno'=>40001,
                'msg'=>'账号不存在',
            ];
            return $response;
        }
        if(password_verify($password,$userInfo->password)){
            $token = substr(md5(time() . mt_rand(1,99999)),10,10);


            $redis_app_token='app:user:login:token'.$userInfo->id;
            Redis::set($redis_app_token,$token);
            Redis::expire($redis_app_token,time()+86400);

            $response=[
                'erron'=>0,
                'msg'=>'登录成功',
                'token'=>$token,
                'id'=>$userInfo->id
            ];

        }else{
            $response=[
                'erron'=>40000,
                'msg'=>'账号或密码错误',
            ];
        }
        return $response;
    }


    //互踢
    public function center(Request $request){
        $token = $request->input('token');
        $uid = $request->input('uid');
        $redis_token= Redis::get("app:user:login:token$uid");
        /*echo $redis_token;
        echo $token;*/
        if($token == $redis_token){
            return 1;
        }else{

            return 0;
        }
    }

    //用户列表
    public function list(){
        $userInfo = LoginModel::get();
        print_r($userInfo);     
    }
}
