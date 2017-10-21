<!doctype html>
<html lang="{{ app()->getLocale() }}" ng-app="welcome">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Noting</title>

        <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
        <script src="/node_modules/jquery/dist/jquery.js"></script>
        <script src="/node_modules/angular/angular.js"></script>
        <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>

        <script src="/js/welcome.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/welcome.css"
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/animate-custom.css" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    </head>
    <body class="background">

            <div class="container">

                <header>
                    <div class="guide_noting">Noting</div>
                    <div class="guide_intro">开启你的记录</div>
                </header>

                <section>
                    <div id="container_demo" >
                        <!-- hidden anchor to stop jump http://www.css3create.com/Astuce-Empecher-le-scroll-avec-l-utilisation-de-target#wrap4  -->
                        <a class="hiddenanchor" id="toregister"></a>
                        <a class="hiddenanchor" id="tologin"></a>
                        <div id="wrapper">
                            <div id="login" class="animate form">
                                <form  action="#" autocomplete="on">
                                    <h1>Log in</h1>
                                    <p>
                                        <label for="username" class="uname" data-icon="u" > Your email or username </label>
                                        <input id="username" name="username" required="required" type="text" placeholder="myusername or mymail@mail.com"/>
                                    </p>
                                    <p>
                                        <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                                        <input id="password" name="password" required="required" type="password" placeholder="eg. X8df!90EO" />
                                    </p>
                                    <p class="keeplogin">
                                        <input type="checkbox" name="loginkeeping" id="loginkeeping" value="loginkeeping" />
                                        <label for="loginkeeping">Keep me logged in</label>
                                    </p>
                                    <p class="login button">
                                        <input type="submit" value="Login" />
                                    </p>
                                    <p class="change_link">
                                        Not a member yet ?
                                        <a href="#toregister" class="to_register">Join us</a>
                                    </p>
                                </form>
                            </div>

                            <div id="register" class="animate form">
                                <form  action="#" autocomplete="on">
                                    <h1> Sign up </h1>
                                    <p>
                                        <label for="usernamesignup" class="uname" data-icon="u">Your username</label>
                                        <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="mysuperusername690" />
                                    </p>
                                    <p>
                                        <label for="emailsignup" class="youmail" data-icon="e" > Your email</label>
                                        <input id="emailsignup" name="emailsignup" required="required" type="email" placeholder="mysupermail@mail.com"/>
                                    </p>
                                    <p>
                                        <label for="passwordsignup" class="youpasswd" data-icon="p">Your password </label>
                                        <input id="passwordsignup" name="passwordsignup" required="required" type="password" placeholder="eg. X8df!90EO"/>
                                    </p>
                                    <p>
                                        <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Please confirm your password </label>
                                        <input id="passwordsignup_confirm" name="passwordsignup_confirm" required="required" type="password" placeholder="eg. X8df!90EO"/>
                                    </p>
                                    <p class="signin button">
                                        <input type="submit" value="Sign up"/>
                                    </p>
                                    <p class="change_link">
                                        Already a member ?
                                        <a href="#tologin" class="to_register"> Go and log in </a>
                                    </p>
                                </form>
                            </div>

                        </div>
                    </div>
                </section>
            </div>

    </body>
</html>
