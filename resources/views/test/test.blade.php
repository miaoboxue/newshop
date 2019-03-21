@extends('user.user')

@section('title') {{$title}}    @endsection

@section('header')
    @parent
    <p style="color: red;">This is Child header.</p>
@endsection

@section('content')
    <p>这里是 Child Content.
    <table border="1">
        <thead>
            <tr>
                <td>id</td>
                <td>姓名</td>
                <td>年龄</td>
                <td>邮箱</td>
                <td>时间</td>
            </tr>
        </thead>
        <tbody>
            @foreach($info as $v)
                <tr>
                    <td>{{$v['uid']}}</td>
                    <td>{{$v['name']}}</td>
                    <td>{{$v['age']}}</td>
                    <td>{{$v['email']}}</td>
                    <td>{{date('Y-m-d H:i:s',$v['reg_time'])}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection


@section('footer')
    @parent
    <p style="color: red;">This is Child footer .</p>
@endsection