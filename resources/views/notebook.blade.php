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


            <select ngc-select-search class="common-select" ng-model="aa.b" ng-options="notebook.id as notebook.title for notebook in notebooks" name="notebook">
                <option value="">请选择</option>
            </select>

            <label>选择一本笔记本:
                <select ng-model="colorChosen" ng-options="notebook.id as notebook.title for notebook in notebooks">

                </select>
            </label>
        </div>

        </div>


    </div>
</body>
</html>