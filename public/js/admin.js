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
                    templateUrl: "/log-viewer"
                })
        }
    ]);

    app.controller('adminCrtl',function($scope,$http,$cookieStore,SweetAlert,$timeout) {
        $scope.userList = function () {
            $http.get('/api/admin/adminUserList')
                .then(function (response){
                    if(response.data.status){//有用户
                        $scope.data = response.data.data;

                        //分页总数
                        $scope.pageSize = 10;
                        $scope.pages = Math.ceil($scope.data.length / $scope.pageSize); //分页数
                        $scope.newPages = $scope.pages > 5 ? 5 : $scope.pages;
                        $scope.pageList = [];
                        $scope.selPage = 1;

                        //设置表格数据源(分页)
                        $scope.setData = function () {
                            $scope.users = $scope.data.slice(($scope.pageSize * ($scope.selPage - 1)), ($scope.selPage * $scope.pageSize));//通过当前页数筛选出表格当前显示数据
                        }
                        $scope.users = $scope.data.slice(0, $scope.pageSize);
                        //分页要repeat的数组
                        for (var i = 0; i < $scope.newPages; i++) {
                            $scope.pageList.push(i + 1);
                        }
                        //打印当前选中页索引
                        $scope.selectPage = function (page) {
                            //不能小于1大于最大
                            if (page < 1 || page > $scope.pages) return;
                            //最多显示分页数5
                            if (page > 2) {
                                //因为只显示5个页数，大于2页开始分页转换
                                var newpageList = [];
                                for (var i = (page - 3) ; i < ((page + 2) > $scope.pages ? $scope.pages : (page + 2)) ; i++) {
                                    newpageList.push(i + 1);
                                }
                                $scope.pageList = newpageList;
                            }
                            $scope.selPage = page;
                            $scope.setData();
                            $scope.isActivePage(page);
                            console.log("选择的页：" + page);
                        };
                        //设置当前选中页样式
                        $scope.isActivePage = function (page) {
                            return $scope.selPage == page;
                        };
                        //上一页
                        $scope.Previous = function () {
                            $scope.selectPage($scope.selPage - 1);
                        }
                        //下一页
                        $scope.Next = function () {
                            $scope.selectPage($scope.selPage + 1);
                        };
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
                            id: $scope.users[$index].id
                        }
                    })
                        .then(function (response) {
                            if (response.data.status) {//删除成功
                                $scope.users[$index].deleted_at = true;
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

        $scope.recover = function ($index) {
            if($index>=0){
                if(confirm("是否恢复"+$scope.users[$index].name) ){
                    $http.get('/api/admin/recoverUser', {
                        params: {
                            id: $scope.users[$index].id
                        }
                    })
                        .then(function (response) {
                            if (response.data.status) {//恢复
                                $scope.users[$index].deleted_at = false;
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
        // $scope.save = function(){
        //     //将添加的值赋给数组
        //     $scope.users.name = $scope.newName;
        //     $scope.users.age = $scope.newAge;
        //     $scope.users.city = $scope.newCity;
        //     $scope.users.push({name:$scope.newName,age:$scope.newAge,city:$scope.newCity});
        //     //关闭模块窗口
        //     $('#modal-1').modal('hide');
        // };


        //定义一个点击修改按钮时出发的事件，用于修改数据
        $scope.update = function($index){
            //显示bootstrap中的模块窗口
            $('#modal-2').modal('show');
            //将选中行的数据绑定到临时对象prod中，在下面的模态窗口中展示出来
            $scope.prod.id = $scope.users[$index].id;
            $scope.prod.name = $scope.users[$index].name;
            $scope.prod.email = $scope.users[$index].email;
            //选中行的索引赋值给全局变量idx
            idx = $index;
        };

        //定义一个点击确定按钮时触发的事件,
        $scope.ensure = function () {
            $http.post('/api/admin/reviseEmail', {
                id: $scope.prod.id,
                email: $scope.prod.email
            })
                .then(function (response) {
                    if (response.data.status) {//修改成功
                        console.log('hi',$scope.prod.email)
                        //将修改后的值赋给数组
                        $scope.users[idx].name = $scope.prod.name;
                        $scope.users[idx].email = $scope.prod.email;
                        //关闭模块窗口
                        $('#modal-2').modal('hide');
                    } else {
                        console.log(response.data.msg);
                        //关闭模块窗口
                        $('#modal-2').modal('hide');
                    }
                }), function () {
                console.log('e');
            }
        };

        $scope.logout = function(){

        };
    });

})();



