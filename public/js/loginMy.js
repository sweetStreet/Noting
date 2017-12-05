(function () {
    'user strict';

var app = angular.module('adminLogin',['ui.router','ngAnimate','toastr']);

app.config(['$interpolateProvider','$stateProvider','$urlRouterProvider','$httpProvider',
    function($interpolateProvider,$stateProvider,$urlRouterProvider,$httpProvider) {
        //原本默认的是两个大括号{{内容}}，但是angular和laravel会冲突
        $interpolateProvider.startSymbol("[:");
        $interpolateProvider.endSymbol(":]");
    }
]);

app.controller('adminLoginCtrl',function($scope,$http,toastr) {
    $scope.login = function (user) {
        if(typeof(user)!="undefined"&&user!=[]){
            $http.post('/api/admin/login',{username:user.username,password:user.password})
                .then(function(response){
                    if(response.data.status) {//登录成功
                        window.location.href = response.data.msg;
                    }else{
                        toastr.error(response.data.msg);
                    }
                }),function(){
                toastr.error("网络故障，请重试");
            }
        }
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


