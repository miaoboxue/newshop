@extends('layouts.bst')

@section('content')

<form action="/userlogin" method="post" style="width: 600px; margin-left: 230px;">
    {{csrf_field()}}
    <h2 class="form-signin-heading" style="padding-left: 240px;">User Login</h2>
    <div class="form-group">
        <label for="exampleInputEmail1">NickName</label>
        <input type="text" class="form-control" name="name" placeholder="Nickname" required>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control" name="pwd" placeholder="******" required>
    </div>
    <button type="submit" class="btn btn-default">Login</button>
</form>
@endsection