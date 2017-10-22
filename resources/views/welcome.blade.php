<!doctype html>
<html lang="{{ app()->getLocale() }}" ng-app="welcome">
    <head>
        <title>Noting</title>

        <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
        <script src="/node_modules/jquery/dist/jquery.js"></script>
        <script src="/node_modules/angular/angular.js"></script>
        <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>

        <script src="/js/welcome.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/welcome.css"

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    </head>
    <body class="container">

            <div >
                <header>
                    <div class="guide_noting">Noting</div>
                    <div class="guide_intro">开启你的记录</div>
                </header>

                <form>
                    <div class="inset">
                        <p>
                            <label for="email">EMAIL ADDRESS</label>
                            <input type="text" name="email" id="email">
                        </p>
                        <p>
                            <label for="password">PASSWORD</label>
                            <input type="password" name="password" id="password">
                        </p>
                        <p>
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Remember me</label>
                        </p>

                        <p class="p-container">
                            <input type="submit" name="go" id="go" value="Log in">
                        </p>

                        <p ><a class="link" href="">forget Password ?</a>
                        <p ><a class="link" href="">Not a member yet ?</a>
                    </div>


                </form>

                <footer class="footer">
                    <div> Copyright © 2017 sweetstreet. All rights reserved. </div>
                </footer>
            </div>

    </body>
</html>
