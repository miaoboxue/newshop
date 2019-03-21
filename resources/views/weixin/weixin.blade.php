<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel-Blade</title>
</head>
<body>
    <form action="/formShow" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="text" name="aaa"><br><br>
        <input type="file" name="media"><br><br>
        <input type="submit" value="提交">
    </form>
</body>
</html>
