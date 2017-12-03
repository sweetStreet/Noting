(function () {
    'user strict';

    var app = angular.module('admin',['ui.router','ngCookies','socialbase.sweetAlert']);
    app.config(['$interpolateProvider','$stateProvider','$urlRouterProvider','$httpProvider',
        function($interpolateProvider,$stateProvider,$urlRouterProvider,$httpProvider) {
            //原本默认的是两个大括号{{内容}}，但是angular和laravel会冲突
            $interpolateProvider.startSymbol("[:");
            $interpolateProvider.endSymbol(":]");

            $urlRouterProvider.otherwise("/");
            $stateProvider
                .state('user', {
                    url: "/user",
                    templateUrl: "user.tpl"
                })
                .state('log', {
                    url: "/log",
                    templateUrl: "log.tpl"
                })
        }
    ]);


    app.controller('adminCrtl',function($scope,$http,$cookieStore,SweetAlert,$timeout) {

    });





})();



