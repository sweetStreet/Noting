(function () {
    'user strict';

    var app = angular.module('notebook',['ui.router','ngCookies','ngCookies','htmlToPdfSave']);

    app.config(['$interpolateProvider','$stateProvider','$urlRouterProvider',
        function($interpolateProvider,$stateProvider,$urlRouterProvider) {
            //原本默认的是两个大括号{{内容}}，但是angular和laravel会冲突
            $interpolateProvider.startSymbol("[:");
            $interpolateProvider.endSymbol(":]");

        }
    ]);

    app.controller('notebookCrtl',function($scope,$http,$cookieStore) {
        //页面初始化
        $scope.init = function () {
            userid = $cookieStore.get('userid');
            console.log(userid);
            $scope.notebooks = [];
            $scope.articles = [];
            $http.get('/api/notebook/getAll', {
                params: {
                    "userid": userid
                }
            })
                .then(function (response) {
                    if (response.data.status) {//有笔记本
                        $scope.notebooks = response.data.notebooks;
                        console.log(response.data.notebooks);
                    } else {
                        console.log('没有数据');
                    }
                }), function () {
                console.log('e');
            }
        };

        //保存文章
        $scope.saveArticle = function () {
            articleid = $cookieStore.get('articleid');
            content = editor.txt.html();
            userid = $cookieStore.get('userid');
            notebookid = $cookieStore.get('notebookid');
            $http.post('/api/article/saveArticle', {
                article_id: articleid,
                user_id: userid,
                notebook_id: notebookid,
                content: content
            })
                .then(function (response) {
                    if (response.data.status) {//创建成功
                        //刷新文章列表
                        $scope.updateList();
                        console.log('success');
                    } else {
                        //创建失败
                        console.log('error');
                    }
                }), function () {
                console.log('e');
            }
        }

        //将左侧列表选中的文章显示在右侧编辑器上
        $scope.showInEditor = function (article) {
            editor.txt.html(article.content);
            $cookieStore.put('articleid', article.id);
        }

        //刷新文章列表
        $scope.updateList = function () {
            userid = $cookieStore.get('userid');
            notebookid = $cookieStore.get('notebookid');
            if (notebookid == '') {
                //提示请选择笔记本
            } else {
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
            $http.post('/api/article/addArticle', {user_id: userid, notebook_id: notebookid, content: editorContent})
                .then(function (response) {
                    if (response.data.status) {//创建成功
                        //刷新文章列表
                        $scope.updateList();
                        $cookieStore.put('articleid', $scope.articles[0].id);
                        console.log('success');
                    } else {
                        //创建失败
                        console.log('error');
                    }
                }), function () {
                console.log('e');
            }
        }

        //删除文章
        $scope.deleteArticle = function () {
            editor.txt.html('<p><br></p>');
            $articleid = $cookieStore.get('articleid')
            $http.get('/api/article/deleteArticle', {params: {article_id: $articleid}})
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
        $scope.selectNotebook = function (notebook) {
            userid = $cookieStore.get('userid');
            notebookid = notebook.id;
            console.log(notebookid);
            $cookieStore.put('notebookid', notebookid);
            $http.get('/api/article/getArticlesByNotebookID', {params: {user_id: userid, notebook_id: notebookid}})
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

        $scope.addNotebook=function () {

        }

        $scope.shareNotebook=function (notebook) {
            console.log()

        }

        $scope.deleteNotebook=function (notebook) {

        }

    })

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
