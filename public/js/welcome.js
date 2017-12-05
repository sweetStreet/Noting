(function () {
    'user strict';
    
    var app = angular.module('welcome',['ui.router','ngCookies','socialbase.sweetAlert','ngAnimate', 'toastr']);
    app.config(['$interpolateProvider','$stateProvider','$urlRouterProvider','$httpProvider',
            function($interpolateProvider,$stateProvider,$urlRouterProvider,$httpProvider) {
                //原本默认的是两个大括号{{内容}}，但是angular和laravel会冲突
                $interpolateProvider.startSymbol("[:");
                $interpolateProvider.endSymbol(":]");

                $httpProvider.defaults.withCredentials=true;

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

        app.controller('welcomeCrtl',function($scope,$http,$cookieStore,SweetAlert,$timeout,toastr){
            $scope.login = function (user) {
                $http.post('/api/user/login',{email:user.email,password:user.password})
                                .then(function(response){
                                    if(response.data.status) {//登录成功
                                        $cookieStore.put('userid',response.data.userid);
                                        window.location.href = response.data.msg;
                                    }else{
                                        toastr.error(response.data.msg);
                                    }
                                }),function(){
                                    console.log('e');
                                }
            }

            $scope.register = function (user) {
                $scope.myTxt = 'clicked';
                $http.post('/api/user/register',{email:user.email,username:user.username,password:user.password})
                    .then(function(response){
                        if(response.data.status){//注册成功
                            SweetAlert.swal({
                                    title:'注册成功',
                                    text:'即将跳转到登录页面',
                                    type:'success',
                                    showConfirmButton:false
                                }
                            )
                            //1.2 seconds delay
                            $timeout( function(){
                                window.location.href = response.data.msg;
                            }, 1200 );
                        }else {
                            toastr.error(response.data.msg);
                        }
                    }),function(){
                    toastr.error(responseText.data.msg);
                }
            }
            $scope.reset = function (){
                $cookieStore.put('userid','');
            }

            $scope.logout = function () {

            }
        });

    app.config(function(toastrConfig) {
        angular.extend(toastrConfig, {
            autoDismiss: true,
            containerId: 'toast-container',
            maxOpened: 0,
            newestOnTop: true,
            positionClass: 'toast-bottom-full-width',
            preventDuplicates: false,
            preventOpenDuplicates: false,
            target: 'body',
            timeOut: 1000,
        });
    });

})();



