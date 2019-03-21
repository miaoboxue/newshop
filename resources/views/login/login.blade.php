@extends('login.parent')

@section('content')
    <form action="/userlogin" method="post">
        <h2>登录</h2>
        {{csrf_field()}}
        <table  class="table table-bordered">
            <tr>
                <td>账号</td>
                <td><input type="text" name="name"></td>
            </tr>
            <tr>
                <td>邮箱</td>
                <td><input type="text" name="email"></td>
            </tr><tr>
                <td>密码</td>
                <td><input type="password" name="pwd"></td>
        </table>
        <button class="btn btn-primary">登录</button>
    </form>
@endsection