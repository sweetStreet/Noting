(function () {
    'user strict';

    var app = angular.module('notebook',['ui.router','ngCookies','btorfs.multiselect']);

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
            $scope.html = '<span style="color: red">这是格式化的HTML文本</span>';
            $scope.notebooks = [];
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

        $scope.saveArticle = function(){
            // alert(editor.txt.html());
            content = editor.txt.html();
            userid = $cookieStore.get('userid');
            notebookid= $scope.notebookSelected[0].id;
            $http.post('/api/article/saveArticle',{user_id:userid,notebook_id:notebookid,content:content})
                .then(function(response){
                    if(response.data.status) {//创建成功
                        //刷新文章列表
                        console.log('success');
                    }else{
                        //创建失败
                        console.log('error');
                    }
                }),function(){
                console.log('e');
            }
        }

        $scope.notebookSelected = [];
        $scope.$watch('notebookSelected',function (newVal) {
            if (newVal.length == 0) {
            } else {
                userid = $cookieStore.get('userid');
                notebookid = newVal[0].id;
                $http.put('/api/article/getArticlesByNotebookID', {user_id: userid, notebook_id: notebookid})
                    .then(function (response) {
                        if (response.data.status) {//获得文章列表
                            if(response.data.data){
                                $scope.articles = response.data.data.data;
                            }
                            console.log('success');
                        } else {
                            console.log('error');
                        }
                    }), function () {
                    console.log('e');
                }
            }
        })
    });

    app.filter('htmlContent',['$sce', function($sce) {
        return function(input) {
            return $sce.trustAsHtml(input);
        }
    }]);

})();
