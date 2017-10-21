;(function () {
    'user strict'
    angular.module('welcome',["ui.router"])
        .config(function($interpolateProvider,$stateProvider,$urlRouterProvider)
    {
        $interpolateProvider.startSymbol("[:");
        $interpolateProvider.endSymbol(":]");

        $urlRouterProvider.otherwise('/home');

        $stateProvider
            .state('home',{
                url:'/home',
                template:'<h1>首页</h1>'
                //templateUrl: '***/***'从服务器上动态获取
            })
            .state('login',{
                url:'/login',
                template:'<h1>登录</h1>'
            })
    })
})();

