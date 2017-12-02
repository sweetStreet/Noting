<!doctype html>
<html ng-app="welcome" ng-controller="welcomeCrtl">
    <head>
        <title>Noting</title>
        <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
        <link rel="stylesheet" href="https://npmcdn.com/sweetalert2@4.0.15/dist/sweetalert2.min.css">
        <link rel="stylesheet" type="text/css" href="/css/welcome.css">
    </head>

    <body class="container">

        <div class="guide_noting">Noting</div>
        <div class="guide_intro">开启你的记录</div>

        <div ui-view></div>

        <footer class="footer">
            <p>[:myTxt:]</p>
            <div> Copyright © 2017 sweetstreet. All rights reserved. </div>
        </footer>



        <script type="text/ng-template" id="login.tpl">
            <div >
                <form>
                    <div class="inset">
                        <p>
                            <label for="email">邮箱</label>
                            <input ng-model="user.email" type="email" name="email" id="email" required>
                        </p>
                        <p>
                            <label for="password">密码</label>
                            <input ng-model="user.password" type="password" name="password" id="password" required>
                        </p>

                        <p class="p-container">
                            <a ui-sref="reset" class="link" >忘记密码 ?</a>
                            <input type="submit" name="go" id="go" value="登录" ng-click="login(user)">
                        </p>

                        <a ui-sref="register" class="link">没有账号 ?</a>
                </form>
            </div>
        </script>

        <script type="text/ng-template" id="register.tpl">
            <div>
                <form>
                    <div class="inset">
                        <p>
                            <label for="email">邮箱地址</label>
                            <input type="email" name="email" id="email" ng-model="user.email" required>
                        </p>
                        <p>
                            <label for="username">用户名</label>
                            <input type="text" name="username" id="username" ng-model="user.username" required>
                        </p>
                        <p>
                            <label for="password">密码</label>
                            <input type="password" name="password" id="password" ng-model="user.password" required>
                        </p>

                        <p class="p-container">
                            <a ui-sref="login" class="link" >已有账号，去登录</a>
                            <input type="submit" name="go" id="go" value="注册" ng-click="register(user)">
                        </p>
                    </div>
        </script>

        <script type="text/ng-template" id="reset.tpl">
            <div>
                <form>
                    <div class="inset">
                        <p>
                            <label for="email">邮箱</label>
                            <input type="text" name="email" id="email">
                        </p>

                        <p class="p-container">
                            <input type="submit" name="go" id="go" value="确认">
                        </p>

                </form>
            </div>
        </script>

        <script src="/node_modules/jquery/dist/jquery.js"></script>
        <script src="/node_modules/angular/angular.js"></script>
        <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
        <script src="/node_modules/angular-cookies/angular-cookies.js"></script>
        <script src="/js/welcome.js"></script>

    </body>
</html>