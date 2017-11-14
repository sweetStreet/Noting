(function () {
    'user strict';

    var app = angular.module('notebook',['ui.router','ngCookies','btorfs.multiselect','ngCookies','htmlToPdfSave']);

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
        //页面初始化
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

        //保存文章
        $scope.saveArticle = function(){
            articleid = $cookieStore.get('articleid');
            content = editor.txt.html();
            userid = $cookieStore.get('userid');
            notebookid= $scope.notebookSelected[0].id;
            $http.post('/api/article/saveArticle',{article_id:articleid,user_id:userid,notebook_id:notebookid,content:content})
                .then(function(response){
                    if(response.data.status) {//创建成功
                        //刷新文章列表
                        $scope.updateList();
                        console.log('success');
                    }else{
                        //创建失败
                        console.log('error');
                    }
                }),function(){
                console.log('e');
            }
        }

        //将左侧列表选中的文章显示在右侧编辑器上
        $scope.showInEditor = function(article){
            editor.txt.html(article.content);
            $cookieStore.put('articleid',article.id);
        }

        //刷新文章列表
        $scope.updateList = function(){
            userid = $cookieStore.get('userid');
            notebookid = $cookieStore.get('notebookid');
            if(notebookid == ''){
                //提示请选择笔记本
            }else {
                $http.get('/api/article/getArticlesByNotebookID', {
                    params: {
                        user_id: userid,
                        notebook_id: notebookid
                    }
                })
                    .then(function (response) {
                        if (response.data.status) {//获得文章列表
                            if (response.data.data) {
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
        }

        //新增文章
        $scope.addArticle = function () {
            editorContent = '<p><br></p>';
            editor.txt.html(editorContent);
            $http.post('/api/article/addArticle',{user_id:userid,notebook_id:notebookid,content:editorContent})
                .then(function(response){
                    if(response.data.status) {//创建成功
                        //刷新文章列表
                        $scope.updateList();
                        $cookieStore.put('articleid',$scope.articles[0].id);
                        console.log('success');
                    }else{
                        //创建失败
                        console.log('error');
                    }
                }),function(){
                console.log('e');
            }
        }

        //删除文章
        $scope.deleteArticle=function(){
            editor.txt.html('<p><br></p>');
            $articleid = $cookieStore.get('articleid')
            $http.get('/api/article/deleteArticle', {params:{article_id:$articleid}})
                .then(function (response) {
                    if (response.data.status) {
                        //刷新文章列表
                        $scope.updateList();
                        console.log('success');
                    } else {
                        console.log('error');
                    }
                }), function () {
                console.log('e');
            }

        }


        //获得属于某个笔记本的所有文章
        $scope.notebookSelected = [];
        $scope.$watch('notebookSelected',function (newVal) {
            if (newVal.length == 0) {
                $cookieStore.put('notebookid','');
            } else {
                userid = $cookieStore.get('userid');
                notebookid = newVal[0].id;
                $cookieStore.put('notebookid',notebookid);
                $http.get('/api/article/getArticlesByNotebookID', {params:{user_id: userid, notebook_id: notebookid}})
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

    app.directive('ngToYellow', function() {
        return {
            restrict: 'AE',
            replace: true,
            link: function(scope, elem, attrs) {
                elem.bind('click', function() {

                    var divs = document.getElementsByName("article_item");
                    var len = divs.length;
                    for(var i=0;i<len;i++){
                            for(var j=0;j<len;j++) {
                                divs[j].style.backgroundColor = "white";
                                divs[j].style.color="black";
                            }
                    }

                        elem.css("background-color", "rgb(254,198,6)");
                        elem.css("color", "white");


                });
            }
        };
    });

    app.filter('htmlContent',['$sce', function($sce) {
        return function(input) {
            return $sce.trustAsHtml(input);
        }
    }]);

})();
