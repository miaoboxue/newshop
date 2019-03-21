@extends('layouts.bst')

@section('content')
    <form action="/userreg" method="post" class="form-signin" style="width: 600px; margin-left: 230px;">
        {{csrf_field()}}
            <h3 class="form-signin-heading" style="padding-left: 240px;">User Register</h3>
            <div class="form-group">
                <label for="exampleInputEmail1">NickName</label>
                <input type="text" class="form-control" name="name" placeholder="Nickname" required>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" name="pwd" placeholder="***" required>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Confirm Password</label>
                <input type="password" class="form-control" name="pwd1" placeholder="***" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Age</label>
                <input type="text" class="form-control" name="age" placeholder="Age" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" name="email" placeholder="@" required>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Check me out
                </label>
            </div>
            <button type="submit" class="btn btn-default">Register</button>
    </form>
@endsection
