<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="/node_modules/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/css/loginMy.css"/>
</head>
<body>
<div class="main">
    <div id="title">Noting后台管理系统</div>
    <div class="center">
        <form action="" id="formOne" method="post"onsubmit="return submitB()" >
            <i class="fa fa-user Cone">  | </i>
            <input type="text" name="uer" id="user" placeholder="用户名"onblur="checkUser()"/>
            <span id="user_pass"></span>
            <br/>
            <i class="fa fa-key Cone">  | </i>
            <input type="password" name="pwd" id="pwd" placeholder="密码"onblur="checkUser1()"/>
            <span id="pwd_pass"></span>
            <br/>
            <input type="submit" value="Sign in" id="submit" name="submit">
            <br/>
        </form>
    </div>

</div>
<script type="text/javascript" src="/js/loginMy.js"></script>
</body>
</html>