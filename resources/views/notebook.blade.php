<!doctype html>
<html ng-app="notebook" ng-controller="notebookCrtl" ng-init="init()">
<head>
    <title>notebook</title>

    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>

    <link rel="stylesheet" href="https://npmcdn.com/sweetalert2@4.0.15/dist/sweetalert2.min.css">
    <script src="https://npmcdn.com/sweetalert2@4.0.15/dist/sweetalert2.min.js"></script>
    <script src="http://cdn.static.runoob.com/libs/angular.js/1.4.6/angular-animate.min.js"></script>

    <link href="/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="/node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="/node_modules/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" type="text/css"/>

    <script src="/js/notebook.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/notebook.css">
    <script src="/node_modules/angular-cookies/angular-cookies.js"></script>
</head>
<body>
    <div class="container">
        <div class="notebook-item">
            <div ng-repeat="notebook in notebooks" class="search-notebook">
                <td>[:notebook.title:]</td>
            </div>


                <select id="notebook-select">
                    <option  class="has-children">bug</option>
                    <option  class="has-children">bug</option>
                <select class="notebook-select">
        </div>

        </div>


    </div>
</body>
</html>