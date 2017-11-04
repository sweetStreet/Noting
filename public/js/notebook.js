(function () {
    'user strict';

    var app = angular.module('notebook',['ui.router','ngCookies']);

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
                .state('notebook', {
                    url: '/notebook',
                    templateUrl: "notebook"
                })


            //没有满足上面的所有规则
            $urlRouterProvider.otherwise('/notebook');

        }
    ]);

    app.controller('notebookCrtl',function($scope,$http,$cookieStore){

        $scope.init = function(){
            userid = $cookieStore.get('userid');
            console.log(userid);

            $http.get('/api/notebook/getAll',{
                params: {
                    "userid":userid
                }
            })
                .then(function(response){
                    if(response.data.status) {//有笔记本
                        $scope.notebooks = response.data.notebooks;
                        console.log(response.data.notebooks);
                    }else{
                        console.log('没有数据');
                    }
                }),function(){
                console.log('e');
                }
        };
    });

    app.directive('select', [function() {
        return function (scope, element, attributes) {
            var lastSelected = $('notebook-select option:selected').val();
            // Below setup the dropdown:
            element.multiselect({
                templates: {
                    ul: '<ul class="multiselect-container dropdown-menu with-inputType-none"></ul>',
                    divider: '<li class="multiselect-item divider"></li>',
                    filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>'
                },
                placeholder: "请选择",
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,//不区分大小写
                filterBehavior: 'value',//根据value或者text过滤
                onChange: function(element, checked) {
                    if (confirm('Do you wish to change the selection?')) {
                        lastSelected = element.val();
                    }
                    else {
                        element.multiselect('select', lastSelected);
                        element.multiselect('deselect', element.val());
                    }
                }
            })
        }
    }]);



})();
