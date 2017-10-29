(function () {
    'user strict';
    var app = angular.module('welcome',['ui.router']);
    app.config(['$interpolateProvider','$stateProvider','$urlRouterProvider',
            function($interpolateProvider,$stateProvider,$urlRouterProvider) {
                //原本默认的是两个大括号{{内容}}，但是angular和laravel会冲突
                $interpolateProvider.startSymbol("[:");
                $interpolateProvider.endSymbol(":]");

                //定义了路由规则
                $stateProvider
                // .state('home',{
                //     url:'/home',//在地址栏的显示
                //     template:'<h1>首页</h1>'//模版，这一页需要的html在哪里，把模版插入需要的位置
                //     //会被动态插入ui-view
                //     /*会在html（blade.php）中检查是不是有相同名字
                //     *／例如templateUrl:'home.tpl'
                //     * 如果在index.blade.php中定义了(在当前所有.tpl中找)
                //     * <script type="text/ng-template" id="home.tpl"></script>
                //     * 如果找不到就去服务器上找
                //     * 找到后插入到<div ui-view></div>中
                //      */
                //     //templateUrl: '***/***'从服务器上动态获取
                // })

                    .state('login', {
                        url: '/login',
                        templateUrl: "login.tpl"
                    })

                    .state("register", {
                        url: '/register',
                        templateUrl: "register.tpl"
                    })

                    .state("reset", {
                        url: '/reset',
                        templateUrl: "reset.tpl"
                    })
                //没有满足上面的所有规则
                $urlRouterProvider.otherwise('/login');

            }
        ]);


        app.controller('welcomeCrtl',function($scope,$http,$state){
            $scope.myTxt = "你还没有提交";
            $scope.login = function (user) {
                $http.post('/api/user/login',{email:user.email,password:user.password})
                                .then(function(response){
                                    // $scope.myTxt = response.data;
                                    if(response.data.status)
                                        //$state.go('notebook');
                                    window.location.href="/api/notebook";
                                    console.log("success");
                                }),function(){
                                    console.log('e');
                                }
            }

            $scope.register = function (user) {
                $scope.myTxt = 'clicked';
                $http.post('/api/user/register',{email:user.email,username:user.username,password:user.password})
                    .then(function(response){
                        //$location.path('/resources/views/welcome/blade.php');
                        // $scope.myTxt = response.data;
                        console.log('success');
                    }),function(){
                        console.log('error');
                }
            }
            $scope.reset = function () {

            }
        });

    // app.animation('.fad', function () {
    //     return {
    //         enter: function(element, done) {
    //             element.css({
    //                 opacity: 0
    //             });
    //             element.animate({
    //                 opacity: 1
    //             }, 1000, done);
    //         },
    //         leave: function (element, done) {
    //             element.css({
    //                 opacity: 1
    //             });
    //             element.animate({
    //                 opacity: 0
    //             }, 1000, done);
    //         }
    //     };
    // });

        // .service('UserService',['$http',
        //     function ($http) {
        //         var me = this;
        //         me.registerData = {};
        //         me.register = function () {
        //             $http.post('/api/user/register');
        //         }
        //
        //         me.login = function(){
        //             console.log("hey");
        //             $http.post('/api/user/login');
        //         }
        //
        //         me.reset = function(){
        //
        //         }
        //
        //         me.username_exists = function (){
        //             $http.post('api/user/exists',{username:me.registerData.username})
        //                 .then(function(){
        //                     console.log('r',r);
        //                 }),function(){
        //                     console.log('e',e);
        //             }
        //         }
        //
        //     }
        // ])
        //
        // .controller('RegisterController',['$scope','UserService',
        //     function ($scope,UserService) {
        //     $scope.User = UserService;
        //     $scope.$watch(function () {
        //         return UserService.registerData;
        //     },function (newVal) {  })
        // }])

})();



