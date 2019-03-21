@extends('login.parent')

@section('content')
@if('is_login'==0)
    <a href="http://passport.shop.com/api/apilogin" >登录</a>
@else
    <a> 个人中心 </a>
@endif
@endsection