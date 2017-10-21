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
        <link rel="stylesheet" href="/css/welcome.css"

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    </head>
    <body class="background">
            <div class="navbar">导航栏</div>
            <div ui-view></div>
    </body>
</html>
