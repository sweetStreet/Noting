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
        //页面初始化
        $scope.init = function(){
            userid = $cookieStore.get('userid');
            console.log(userid);
            $scope.html = '<span style="color: red">这是格式化的HTML文本</span>';
            $scope.notebooks = [];
            $scope.editorContent='';
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

        //将左侧列表选中的文章显示在右侧编辑器上
        $scope.showInEditor = function(article){
            $scope.editorContent = article.content;
            console.log($scope.editorContent);
        }

        //新增文章
        $scope.addArticle = function () {
            editor.txt.html('<p><br></p>');
            $http.post('/api/article/addArticle',{user_id:userid,notebook_id:notebookid,content:content})
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

        //获得属于某个笔记本的所有文章
        $scope.notebookSelected = [];
        $scope.$watch('notebookSelected',function (newVal) {
            if (newVal.length == 0) {
            } else {
                userid = $cookieStore.get('userid');
                notebookid = newVal[0].id;
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

    app.directive('contenteditable', function() {
        return {
            restrict: 'A' ,
            require: '?ngModel',
            link: function(scope, element, attrs, ngModel) {
                if(!ngModel){
                    return;
                }
                ngModel.$render = function(){
                    element.html(ngModel.$viewValue||'');
                }
                element.on('blur keyup change',function(){
                    scope.$apply(readViewText);
                });

                function  readViewText() {
                    var html = element.html();
                    if (attrs.stripBr && html === '<br>') {
                        html = '';
                    }
                    ngModel.$setViewValue(html);
                }

                // 创建编辑器
                var editor = new wangEditor('#div1','#div2');

                editor.customConfig.uploadImgServer = '/upload'  // 上传图片到服务器
                editor.customConfig.linkImgCallback = function (url) {
                    console.log(url) // url 即插入图片的地址
                }
//TODO 配置debug模式 记得结束之后删除
                editor.customConfig.debug = true;

                editor.customConfig.onchange = function (html) {
                    console.log(scope.editorContent);
                }
// 自定义 onchange 触发的延迟时间，默认为 200 ms
                editor.customConfig.linkCheck = function (text, link) {
                    console.log(text) // 插入的文字ds
                    console.log(link) // 插入的链接
                    return true // 返回 true 表示校验成功
                    // return '验证失败' // 返回字符串，即校验失败的提示信息
                }

                editor.customConfig.uploadImgServer = '/api/article/img/upload';
                editor.customConfig.uploadFileName = 'myFileName';
// // 将图片大小限制为 5M
// editor.customConfig.uploadImgMaxSize = 5 * 1024 * 1024
// // 限制一次最多上传 15 张图片
// editor.customConfig.uploadImgMaxLength = 15
                editor.customConfig.uploadImgHooks = {
                    before: function (xhr, editor, files) {
                        // 图片上传之前触发
                        // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，files 是选择的图片文件

                        // 如果返回的结果是 {prevent: true, msg: 'xxxx'} 则表示用户放弃上传
                        // return {
                        //     prevent: true,
                        //     msg: '放弃上传'
                        // }
                    },
                    success: function (xhr, editor, result) {
                        // 图片上传并返回结果，图片插入成功之后触发
                        // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
                    },
                    fail: function (xhr, editor, result) {
                        // 图片上传并返回结果，但图片插入错误时触发
                        // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
                    },
                    error: function (xhr, editor) {
                        // 图片上传出错时触发
                        // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
                    },
                    timeout: function (xhr, editor) {
                        // 图片上传超时时触发
                        // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
                    },

                    // 如果服务器端返回的不是 {errno:0, data: [...]} 这种格式，可使用该配置
                    // （但是，服务器端返回的必须是一个 JSON 格式字符串！！！否则会报错）
                    customInsert: function (insertImg, result, editor) {
                        // 图片上传并返回结果，自定义插入图片的事件（而不是编辑器自动插入图片！！！）
                        // insertImg 是插入图片的函数，editor 是编辑器对象，result 是服务器端返回的结果

                        // 举例：假如上传图片成功后，服务器端返回的是 {url:'....'} 这种格式，即可这样插入图片：
                        var data = result.data
                        insertImg(data)
                        // result 必须是一个 JSON 格式字符串！！！否则报错
                    }
                }
                editor.create();
                editor.fullscreen = {
                    // editor create之后调用
                    init: function(editorSelector){
                        $(editorSelector + " .w-e-toolbar").append('<div class="w-e-menu"><a class="_wangEditor_btn_fullscreen" href="###" onclick="editor.fullscreen.toggleFullscreen(\'' + editorSelector + '\')">全屏</a></div>');
                    },
                    toggleFullscreen: function(editorSelector){
                        $(editorSelector).toggleClass('fullscreen-editor');
                        if($(editorSelector + ' ._wangEditor_btn_fullscreen').text() == '全屏'){
                            $(editorSelector + ' ._wangEditor_btn_fullscreen').text('退出全屏');
                        }else{
                            $(editorSelector + ' ._wangEditor_btn_fullscreen').text('全屏');
                        }
                    }
                };
            }
        };
    });

    app.filter('htmlContent',['$sce', function($sce) {
        return function(input) {
            return $sce.trustAsHtml(input);
        }
    }]);

})();
