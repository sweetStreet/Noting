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
                    templateUrl: "/api/logs"
                })
        }
    ]);

    app.controller('adminCrtl',function($scope,$http,$cookieStore,SweetAlert,$timeout) {
        $scope.userList = function () {
            $http.get('/api/admin/adminUserList')
                .then(function (response){
                    if(response.data.status){//有笔记本
                        $scope.users = response.data.data;
                        console.log(response.data.data);
                    }else {
                        console.log('没有数据');
                    }
                }), function () {
                console.log('e');
            }
        }


        //定义一个空对象，用于保存和修改数据时临时存储
        $scope.prod = {};
        $scope.key='';
        //定义一个单击删除按钮时触发的事件，用于删除选中行
        // $scope.del = function ($index) {
        //     if($index>=0){
        //         if(confirm("是否删除"+$scope.users[$index].name) ){
        //             $http.get('/api/admin/adminDeleteUser', {
        //                 params: {
        //                     email: $scope.users[$index].email
        //                 }
        //             })
        //                 .then(function (response) {
        //                     if (response.data.status) {//删除成功
        //                         $scope.users.splice($index,1);
        //                         console.log(response.data.msg);
        //                     } else {
        //                         console.log('没有数据');
        //                     }
        //                 }), function () {
        //                 console.log('e');
        //             }
        //         }
        //     }
        // };
        $scope.del = function ($index) {
            if($index>=0){
                if(confirm("是否删除"+$scope.users[$index].name) ){
                    $http.get('/api/admin/adminDeleteUser', {
                        params: {
                            email: $scope.users[$index].email
                        }
                    })
                        .then(function (response) {
                            if (response.data.status) {//删除成功
                                $scope.users.deleted_at = true;
                                console.log(response.data.msg);
                            } else {
                                console.log(response.data);
                            }
                        }), function () {
                        console.log('e');
                    }
                }
            }
        };


        // //定义一个全局变量idx,用于存储选中行的索引，方便执行保存操作。idx取值为0、1、、、、都有用，所以暂取值为-1;
        // var idx = -1;
        // //定义一个点击添加按钮时触发的事件，用于新增数据
        // $scope.add = function(){
        //     //显示bootstrap中的模块窗口
        //     $('#modal-1').modal('show');
        //
        // };
        //定义一个点击保存按钮时触发的事件
        $scope.save = function(){
            //将添加的值赋给数组
            $scope.users.name = $scope.newName;
            $scope.users.age = $scope.newAge;
            $scope.users.city = $scope.newCity;
            $scope.users.push({name:$scope.newName,age:$scope.newAge,city:$scope.newCity});
            //关闭模块窗口
            $('#modal-1').modal('hide');
        };


        //定义一个点击修改按钮时出发的事件，用于修改数据
        $scope.update = function($index){
            //显示bootstrap中的模块窗口
            $('#modal-2').modal('show');

            //将选中行的数据绑定到临时对象prod中，在下面的模态窗口中展示出来
            $scope.prod.name = $scope.users[$index].name;
            $scope.prod.email = $scope.users[$index].email;
            //选中行的索引赋值给全局变量idx
            idx = $index;
        };

        //定义一个点击确定按钮时触发的事件,
        $scope.ensure = function () {
            //将修改后的值赋给数组
            $scope.users[idx].name = $scope.prod.name;
            $scope.users[idx].email = $scope.prod.email;
            //关闭模块窗口
            $('#modal-2').modal('hide');
        };
    });

})();



