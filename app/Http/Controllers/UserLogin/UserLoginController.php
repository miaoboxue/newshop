<?php

namespace App\Http\Controllers\UserLogin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class UserLoginController extends Controller
{
    //

    public function userLogin(Request $request){
        $email = $request -> input('email');
        $password = $request -> input('password');

        $data=[
            'emial'=>$email,
            'password'=>$password
        ];
        $url = "http://passport.miao629.com/apiuser";
        $ch= curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $res = curl_exec($ch);
        return $res;
    }

}
