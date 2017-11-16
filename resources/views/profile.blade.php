<!doctype html>
<html ng-app="notebook" ng-controller="notebookCrtl" ng-init="init()">
<head>
    <title>notebook</title>

    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>

    <link href="/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="/node_modules/angular-bootstrap-multiselect/dist/angular-bootstrap-multiselect.js"></script>

    <script src="/node_modules/angular-cookies/angular-cookies.js"></script>

    <!--font awesome-->
    <!--    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css"/>

    <!--profile的js和css-->
    <script src="/js/profile.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/profile.css">

    <link rel="stylesheet" type="text/css" href="/css/book/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/css/book/demo.css" />
    <link rel="stylesheet" type="text/css" href="/css/book/book2.css" />
    <script src="/js/book/modernizr.custom.js"></script>

</head>
<body>

<div class="component">

    <ul class="align">
        <li>
            <figure class='book'>

                <!-- Front -->

                <ul class='paperback_front'>
                    <li>
                        <span class="ribbon">Nº1</span>
                        <img src="img/bg.jpg" alt="" width="100%" height="100%">
                    </li>
                    <li></li>
                </ul>

                <!-- Pages -->

                <ul class='ruled_paper'>
                    <li></li>
                    <li>
                        <a class="btn" href="http://www.codehero.top">VIEW</a>
                    </li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>

                <!-- Back -->

                <ul class='paperback_back'>
                    <li>
                        <img src="img/bg.jpg" alt="" width="100%" height="100%">
                    </li>
                    <li></li>
                </ul>
                <figcaption>
                    <h1>草图</h1>
                    <span>By 代码侠</span>
                    <p>代码侠是一个分享酷站的网站，这里有很多炫酷的效果展示，也有教程，玩代码，找代码侠。</p>
                </figcaption>
            </figure>
        </li>
        <li>
            <figure class='book'>

                <!-- Front -->

                <ul class='paperback_front'>
                    <li>
                        <span class="ribbon">NEW</span>
                        <img src="/images/paper.jpg" alt="" width="100%" height="100%">
                    </li>
                    <li></li>
                </ul>

                <!-- Pages -->

                <ul class='ruled_paper'>
                    <li></li>
                    <li>
                        <a class="btn" href="http://www.codehero.top">PREVIEW</a>
                    </li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>

                <!-- Back -->

                <ul class='paperback_back'>
                    <li>
                        <img src="/images/paper.jpg" alt="" width="100%" height="100%">
                    </li>
                    <li></li>
                </ul>
                <figcaption>
                    <h1>绘制</h1>
                    <span>By 代码侠</span>
                    <p>代码侠是一个分享酷站的网站，这里有很多炫酷的效果展示，也有教程，玩代码，找代码侠。</p>
                </figcaption>
            </figure>
        </li>
        <li>
            <figure class='book'>

                <!-- Front -->

                <ul class='paperback_front green'>
                    <li></li>
                    <li></li>
                </ul>

                <!-- Pages -->

                <ul class='ruled_paper'>
                    <li></li>
                    <li>
                        <a class="btn" href="http://www.codehero.top">VIEW</a>
                    </li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>

                <!-- Back -->

                <ul class='paperback_back green'>
                    <li></li>
                    <li></li>
                </ul>
                <figcaption>
                    <h1>日记</h1>
                    <span>By 代码侠</span>
                    <p>代码侠是一个分享酷站的网站，这里有很多炫酷的效果展示，也有教程，玩代码，找代码侠。</p>
                </figcaption>
            </figure>
        </li>
        <li>
            <figure class='book'>

                <!-- Front -->

                <ul class='paperback_front red'>
                    <li></li>
                    <li></li>
                </ul>

                <!-- Pages -->

                <ul class='ruled_paper'>
                    <li></li>
                    <li>
                        <a class="btn" href="http://www.codehero.top">VIEW</a>
                    </li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>

                <!-- Back -->

                <ul class='paperback_back red'>
                    <li></li>
                    <li></li>
                </ul>
                <figcaption>
                    <h1>涂鸦</h1>
                    <span>By 代码侠</span>
                    <p>代码侠是一个分享酷站的网站，这里有很多炫酷的效果展示，也有教程，玩代码，找代码侠。</p>
                </figcaption>
            </figure>
        </li>
    </ul>
</div>

</body>
