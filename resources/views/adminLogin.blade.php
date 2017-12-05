<!DOCTYPE html>
<html ng-app="adminLogin" ng-controller="adminLoginCtrl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="/node_modules/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/angular-toastr/dist/angular-toastr.css" />
    <link rel="stylesheet" href="/css/loginMy.css"/>
</head>
<body>
<div class="main">
    <div id="title">Noting后台管理</div>
    <div class="center">
        <form >
            <i class="fa fa-user Cone">  |</i>
            <input type="text" name="uer" id="user" placeholder="用户名" ng-model="user.username" required/>
            <span id="user_pass"></span>
            <br/>
            <i class="fa fa-key Cone">  |</i>
            <input type="password" name="pwd" id="pwd" placeholder="密码" ng-model="user.password" required/>
            <span id="pwd_pass"></span>
            <br/>
            <input type="submit" value="登录" id="submit" name="submit" ng-click="login(user)">
        </form>
    </div>

</div>
<script src="/node_modules/jquery/dist/jquery.js"></script>
<script src="/node_modules/angular/angular.js"></script>
<script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
<script type="text/javascript" src="/node_modules/angular-animate/angular-animate.js"></script>
<script type="text/javascript" src="/node_modules/angular-toastr/dist/angular-toastr.tpls.js"></script>
<script type="text/javascript" src="/js/loginMy.js"></script>
</body>
</html>