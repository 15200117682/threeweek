<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登陆</title>
</head>
<body>
<form action="/user/loginDo" method="post">
    <table>
        <tr>
            <td>用户名</td>
            <td><input type="text" name="email"></td>
        </tr>
        <tr>
            <td>密码</td>
            <td><input type="password" name="pwd"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="login"></td>
        </tr>
    </table>
</form>
</body>
</html>