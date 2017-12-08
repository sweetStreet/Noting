(function () {
    'user strict';

    var app = angular.module('notebook',['ui.router','ngCookies','htmlToPdfSave','socialbase.sweetAlert',
        'angularFileUpload','ngAnimate', 'toastr','720kb.tooltips','angularjs.bootstrap.tagsinput.template','angularjs.bootstrap.tagsinput']);

    app.config(['$interpolateProvider','$stateProvider','$urlRouterProvider',
        function($interpolateProvider,$stateProvider,$urlRouterProvider) {
            //原本默认的是两个大括号{{内容}}，但是angular和laravel会冲突
            $interpolateProvider.startSymbol("[:");
            $interpolateProvider.endSymbol(":]");


            //定义了路由规则
            $stateProvider
                .state('friends', {
                    url: '/friends',
                    templateUrl: "friends.tpl"
                })

                .state("file", {
                    url: '/file',
                    templateUrl: "file.tpl"
                })

                .state("garbage", {
                    url: '/garbage',
                    templateUrl: "garbage.tpl"
                })
            //没有满足上面的所有规则
            $urlRouterProvider.otherwise('/login');

        }]);


    app.controller('notebookCrtl',function($scope,$http,$cookieStore,SweetAlert,FileUploader,toastr) {
        //定义一个空对象，用于保存和修改数据时临时存储
        $scope.prod = {};

        //页面初始化
        $scope.init = function () {
            $scope.notebooks = [];
            $scope.notebookSelected = [];
            $scope.articles = [];
            $scope.photos = [];
            $scope.selectUserNotebooks();
            $scope.selectUserArticles();
        };

            //获得属于某个用户的所有笔记本
            $scope.selectUserNotebooks = function(){
                var userid = $cookieStore.get('userid');
                console.log("selseUserNotebooks");
                console.log(userid);
                $http.get('/api/notebook/getAll', {
                    params: {
                        userid: userid
                    }
                })
                    .then(function (response) {
                        if (response.data.status) {//有笔记本
                            $scope.notebooks = response.data.notebooks;
                        } else {
                            console.log('没有数据');
                        }
                    }), function () {
                    toastr.error('网络故障，请重试');
                }
            }

            //获得属于某个用户的所有文章
            $scope.selectUserArticles = function () {
                var userid = $cookieStore.get('userid');
                $http.get('/api/article/getArticlesByUserID', {
                    params: {
                        user_id: userid
                    }
                })
                    .then(function(response){
                        if (response.data.status) {//有文章
                            $scope.articles = response.data.data;
                            editor.txt.html('<p><br></p>');
                        } else {
                            console.log('没有文章');
                        }
                    }), function () {
                    toastr.error('网络故障，请重试');
                }
            }

        $scope.tags = ['hiho','hohi'];

        $scope.tagsProperties = {
            tagsinputId: '$$$',
            initTags: ['+84111111111', '+84222222222', '+84333333333', '+84444444444', '+84555555555'],
            maxTags: 10,
            maxLength: 15,
            placeholder: 'Please input the phone number'
        };

        $scope.friends = [
            { imgsrc:'/images/avator1.png', name:'muse',email:"151250101@smail.nju.edu.cn"},
            { imgsrc:'/images/avator2.jpeg', name:'大美女',email:"942290857@qq.com"},
            { imgsrc:'/images/avator3.png', name:'petty杨',email:"324345357@qq.com"}
        ]

        // $scope.loadTags = function(query) {
        //     return $http.get('/tags?query=' + query);
        // };

        $scope.onTagsChange = function(data){
            if(typeof($scope.prod.article)!= "undefined"){
                $scope.prod.article.tag = data;
            }
        }
        //保存文章
        $scope.saveArticle = function () {
            if(typeof($scope.prod.article)=="undefined"){
                if(typeof($scope.prod.notebookid)=="undefined"){
                    toastr.error("请先选择笔记本");
                }else {
                    toastr.error("请先选择文章");
                }
            }else {
                var notebookid = $scope.prod.article.notebook_id;
                var articleid = $scope.prod.article.id;
                var userid = $cookieStore.get('userid');
                var content = editor.txt.html();
                var tag = $scope.prod.article.tag;
                console.log('when save article', notebookid);
                $http.post('/api/article/saveArticle', {
                    article_id: articleid,
                    user_id: userid,
                    notebook_id: notebookid,
                    content: content,
                    tag:tag
                })
                    .then(function (response) {
                        if (response.data.status) {//保存成功
                            //更新文章内容
                            $scope.prod.article.content = content;
                            toastr.success("保存成功");
                        } else {
                            //创建失败
                            toastr.error(response.data.msg);
                        }
                    }), function () {
                    toastr.error('网络故障，请重试');
                }
            }
        }

        //将左侧列表选中的文章显示在右侧编辑器上
        $scope.showInEditor = function ($index) {
            editor.txt.html($scope.articles[$index].content);
            $scope.prod.article = $scope.articles[$index];
        }

        //新增文章
        $scope.addArticle = function () {
            var userid = $cookieStore.get('userid');
            var editorContent = '<p><br></p>';
            var notebookid = $scope.prod.notebookid;
            if(typeof(notebookid) == "undefined"){
                toastr.error('请选择笔记本');
            }else{
                $http.post('/api/article/addArticle',{
                    user_id: userid,
                    notebook_id: notebookid,
                    content: editorContent
                })
                    .then(function (response) {
                        if (response.data.status) {//创建成功
                            //刷新文章列表
                            $scope.prod.article = response.data.data;
                            $scope.articles.unshift($scope.prod.article);
                            var x = document.getElementsByClassName("article_item");
                            var i;
                            for (i = 0; i < x.length; i++){
                                x[i].style.backgroundColor = "white";
                                x[i].style.color="black";
                            }
                            editor.txt.html(editorContent);
                            toastr.success('创建成功');
                        } else {
                            toastr.error(response.data.msg);
                        }
                    }), function () {
                        toastr.error('网络故障');
                    }
                }
        }

        //删除文章
        $scope.deleteArticle = function () {
            if(typeof($scope.prod.article)=="undefined"){
                toastr.error('请先选择文章');
            }else {
                console.log("deleteArticle");
                console.log($scope.prod.article);
                $http.get('/api/article/deleteArticle', {params: {article_id: $scope.prod.article.id}})
                    .then(function (response) {
                        if (response.data.status) {
                            editor.txt.html('<p><br></p>');
                            $scope.articles.splice($scope.prod.article,1);
                            $scope.prod.article = undefined;
                            toastr.success('删除成功');
                        } else {
                            toastr.error(response.data.msg);
                        }
                    }), function () {
                    toastr.error('网络故障');
                }
            }
        }


        //获得属于某个笔记本的所有文章
        $scope.selectNotebook = function (notebookid) {
            var userid = $cookieStore.get('userid');
            $http.get('/api/article/getArticlesByNotebookID', {params: {user_id: userid, notebook_id: notebookid}})
                .then(function (response) {
                    if (response.data.status) {//获得文章列表
                        // if (response.data.data) {
                        //     $scope.articles = response.data.data.data;
                        // }
                        $scope.articles = response.data.data;
                        editor.txt.html('<p><br></p>');
                        console.log('获取成功');
                    } else {
                        toastr.error(response.data.msg);
                    }
                }), function () {
                toastr.error("网络故障，请重试");
            }
        }

        //新建笔记本
        $scope.addNotebook=function () {
            var userid = $cookieStore.get('userid');
            swal({
                title: '为你的笔记本取个名字吧',
                input: 'text',
                showCancelButton: true,
                inputValidator: function (value) {
                    return new Promise(function (resolve, reject) {
                        if (value){
                            resolve()
                        }else {
                            reject('你需要输入一些东西')
                        }
                    })
                }
            }).then(function (result) {
                if(result.value){
                    var userid = $cookieStore.get('userid');

                    $http.post('/api/notebook/create', {userid: userid, title: result.value})
                        .then(function (response) {
                            if (response.data.status) {//创建成功
                                $scope.selectUserNotebooks();
                                $scope.selectNotebook(response.data.notebookid);
                                swal({
                                    type: 'success',
                                    html: response.data.msg
                                })
                            } else {
                                console.log(response.data);
                                swal({
                                    type: 'warning',
                                    html: response.data.msg
                                })
                            }
                        }), function () {
                        console.log('e');
                    }
                }else {
                    //没有填写内容
                }

            })
        }

        //按关键字搜索文章
        $scope.searchKeyWord = function(){
            console.log("search");
            var user_id = $cookieStore.get('userid');
            if(typeof($scope.key)!="undefined"||$scope.key!=null||$scope.key!="") {
                $http.post('/api/article/searchContent', {user_id:user_id, keyword:$scope.key})
                    .then(function(response){
                        if (response.data.status) {//查询成功
                            $scope.articles = response.data.data;
                            console.log($scope.articles);
                        } else {

                        }
                    }), function () {
                    console.log('e');
                }
            }
        }

        // //按标签搜索文章
        // $scope.searchKeyWord = function(){
        //     var user_id = $cookieStore.get('userid');
        //     if(typeof($scope.key)!="undefined"||$scope.key!=null||$scope.key!="") {
        //         $http.post('/api/searchByTag', {user_id:user_id, keyword:})
        //             .then(function(response){
        //                 if (response.data.status) {//查询成功
        //                     $scope.articles = response.data.data;
        //                     console.log($scope.articles);
        //                 } else {
        //
        //                 }
        //             }), function () {
        //             console.log('e');
        //         }
        //     }
        // }


        $scope.shareNotebook=function (notebook) {
            console.log()

        }


        //修改个人信息
        $scope.reviseProfile=function(){
            swal({
                title: '个人信息',
                html:
                '用户名<input id="swal-input1" class="swal2-input">' +
                '密码<input id="swal-input2" class="swal2-input">',
                preConfirm: function () {
                    return new Promise(function (resolve) {
                        resolve([
                            $('#swal-input1').val(),
                            $('#swal-input2').val()
                        ])
                    })
                },
                onOpen: function () {
                    $('#swal-input1').focus()
                }
            }).then(function (result) {
                swal(JSON.stringify(result))
            }).catch(swal.noop)
        }

        //左侧弹出框
        $scope.popLeft = function(){
            var userid = $cookieStore.get('userid');
            $http.get('/api/article/getFile', {params: {user_id: userid}})
                .then(function (response) {
                    if (response.data.status) {//获得所有文件
                        $scope.photos = response.data.data;
                        // console.log(response.data.data);
                        // console.log('获取成功');
                    } else {

                    }
                }), function () {
                toastr.error("网络故障，请重试");
            }
        }

        //上传文件
        var uploader = $scope.uploader = new FileUploader({

        });

        // a sync filter
        uploader.filters.push({
            name: 'syncFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                console.log('syncFilter');
                return this.queue.length < 10;
            }
        });

        // an async filter
        uploader.filters.push({
            name: 'asyncFilter',
            fn: function(item /*{File|FileLikeObject}*/, options, deferred) {
                console.log('asyncFilter');
                setTimeout(deferred.resolve, 1e3);
            }
        });

        // CALLBACKS
        uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
            console.info('onWhenAddingFileFailed', item, filter, options);
        };
        uploader.onAfterAddingFile = function(fileItem) {
            console.info('onAfterAddingFile', fileItem);
        };
        uploader.onAfterAddingAll = function(addedFileItems) {
            console.info('onAfterAddingAll', addedFileItems);
        };
        uploader.onBeforeUploadItem = function(item) {
            console.info('onBeforeUploadItem', item);
        };
        uploader.onProgressItem = function(fileItem, progress) {
            console.info('onProgressItem', fileItem, progress);
        };
        uploader.onProgressAll = function(progress) {
            console.info('onProgressAll', progress);
        };
        uploader.onSuccessItem = function(fileItem, response, status, headers) {
            console.info('onSuccessItem', fileItem, response, status, headers);
        };
        uploader.onErrorItem = function(fileItem, response, status, headers) {
            console.info('onErrorItem', fileItem, response, status, headers);
        };
        uploader.onCancelItem = function(fileItem, response, status, headers) {
            console.info('onCancelItem', fileItem, response, status, headers);
        };
        uploader.onCompleteItem = function(fileItem, response, status, headers) {
            console.info('onCompleteItem', fileItem, response, status, headers);
        };
        uploader.onCompleteAll = function() {
            console.info('onCompleteAll');
        };

        console.info('uploader', uploader);

    })

    /*图片点击放大再点击还原*/
    app.directive('enlargePic',function(){//enlargePic指令名称，写在需要用到的地方img中即可实现放大图片
        return{
            restrict: "AE",
            link: function(scope,elem){
                elem.bind('click',function($event){
                    var img = $event.srcElement || $event.target;
                    angular.element(document.querySelector(".mask"))[0].style.display = "block";
                    angular.element(document.querySelector(".bigPic"))[0].src = img.src;
                })
            }
        }
    })
        .directive('closePic',function(){
            return{
                restrict: "AE",
                link: function(scope,elem){
                    elem.bind('click',function($event){
                        angular.element(document.querySelector(".mask"))[0].style.display = "none";
                    })
                }
            }
        });

    // app.directive('bootstrapTagsinput', [function() {
    //
    //         function getItemProperty(scope, property) {
    //             if (!property)
    //                 return undefined;
    //
    //             if (angular.isFunction(scope.$parent[property]))
    //                 return scope.$parent[property];
    //
    //             return function(item) {
    //                 return item[property];
    //             };
    //         }
    //
    //         return {
    //             restrict: 'EA',
    //             scope: {
    //                 model: '=ngModel'
    //             },
    //             template: '<select multiple></select>',
    //             replace: false,
    //             link: function(scope, element, attrs) {
    //                 $(function() {
    //                     if (!angular.isArray(scope.model))
    //                         scope.model = [];
    //
    //                     var select = $('select', element);
    //
    //                     select.tagsinput({
    //                         typeahead : {
    //                             source   : angular.isFunction(scope.$parent[attrs.typeaheadSource]) ? scope.$parent[attrs.typeaheadSource] : null
    //                         },
    //                         itemValue: getItemProperty(scope, attrs.itemvalue),
    //                         itemText : getItemProperty(scope, attrs.itemtext),
    //                         tagClass : angular.isFunction(scope.$parent[attrs.tagclass]) ? scope.$parent[attrs.tagclass] : function(item) { return attrs.tagclass; }
    //                     });
    //
    //                     for (var i = 0; i < scope.model.length; i++) {
    //                         select.tagsinput('add', scope.model[i]);
    //                     }
    //
    //                     select.on('itemAdded', function(event) {
    //                         if (scope.model.indexOf(event.item) === -1)
    //                             scope.model.push(event.item);
    //                     });
    //
    //                     select.on('itemRemoved', function(event) {
    //                         var idx = scope.model.indexOf(event.item);
    //                         if (idx !== -1)
    //                             scope.model.splice(idx, 1);
    //                     });
    //
    //                     // create a shallow copy of model's current state, needed to determine
    //                     // diff when model changes
    //                     var prev = scope.model.slice();
    //                     scope.$watch("model", function() {
    //                         var added = scope.model.filter(function(i) {return prev.indexOf(i) === -1;}),
    //                             removed = prev.filter(function(i) {return scope.model.indexOf(i) === -1;}),
    //                             i;
    //
    //                         prev = scope.model.slice();
    //
    //                         // Remove tags no longer in binded model
    //                         for (i = 0; i < removed.length; i++) {
    //                             select.tagsinput('remove', removed[i]);
    //                         }
    //
    //                         // Refresh remaining tags
    //                         select.tagsinput('refresh');
    //
    //                         // Add new items in model as tags
    //                         for (i = 0; i < added.length; i++) {
    //                             select.tagsinput('add', added[i]);
    //                         }
    //                     }, true);
    //                 });
    //             }
    //         };
    //     }]);

    //将点击的部分背景换成黄色
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
                        elem.css("background-color", "#F6CD61");
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


    app.config(['tooltipsConfProvider', function configConf(tooltipsConfProvider) {
        tooltipsConfProvider.configure({
            'smart': true
        });
    }]);

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


    /**
     * 带筛选功能的下拉框
     * 使用方法 <select ngc-select-search name="select1" ng-options="">
     * 说明[ select 一定要有name,ng-options 属性]
     */
    app.directive('ngcSelectSearch', function($animate, $compile, $parse, $http, $cookieStore) {

        function parseOptions(optionsExp, element, scope) {
            // ngOptions里的正则
            var NG_OPTIONS_REGEXP = /^\s*([\s\S]+?)(?:\s+as\s+([\s\S]+?))?(?:\s+group\s+by\s+([\s\S]+?))?(?:\s+disable\s+when\s+([\s\S]+?))?\s+for\s+(?:([\$\w][\$\w]*)|(?:\(\s*([\$\w][\$\w]*)\s*,\s*([\$\w][\$\w]*)\s*\)))\s+in\s+([\s\S]+?)(?:\s+track\s+by\s+([\s\S]+?))?$/;

            var match = optionsExp.match(NG_OPTIONS_REGEXP);
            if (!(match)) {
                console.log('ng-options 表达式有误')
            }
            var valueName = match[5] || match[7];
            var keyName = match[6];
            var displayFn = $parse(match[2]);
            var keyFn = $parse(match[1]);
            var valuesFn = $parse(match[8]);

            var labelArray = [],
                idArray = [],
                optionValues = [];
            scope.$watch(match[8], function (newValue, oldValue) {
                if (newValue && newValue.length > 0) {
                    optionValues = valuesFn(scope) || [];
                    labelArray = [];
                    idArray = []
                    for (var index = 0, l = optionValues.length; index < l; index++) {
                        var it = optionValues[index];
                        if (match[2] && match[1]) {
                            var localIt = {};
                            localIt[valueName] = it;
                            var label = displayFn(scope, localIt);
                            var dataId = keyFn(scope, localIt);
                            labelArray.push(label);
                            idArray.push(dataId);
                        }
                    }

                    scope.options = {
                        'optionValues': optionValues,
                        'labelArray': labelArray,
                        'idArray': idArray
                    }
                }
            });
        }

        return {
            restrict: 'A',
            require: ['ngModel'],
            priority: 100,
            replace: false,
            scope: true,
            template: '<div class="chose-container">' +
            '<div class="chose-single"><span class="j-view"></span><i class="glyphicon glyphicon-remove"></i></div>' +
            '<div class="chose-drop chose-hide j-drop">' +
            '<div class="chose-search">' +
            '<input class="j-key" type="text" autocomplete="off">' +
            '</div>' +
            '<ul class="chose-result">' +
            // '<li ng-repeat="'+repeatTempl+'" data-id="'+keyTempl+'" >{{'+ valueTempl+'}}</li>'+
            '</ul>' +
            '</div>' +
            '</div>',
            link: {
                pre: function selectSearchPreLink(scope, element, attr, ctrls) {

                    var tmplNode = $(this.template).first();

                    var modelName = attr.ngModel,
                        name = attr.name ? attr.name : ('def' + Date.now());
                    tmplNode.attr('id', name + '_chosecontianer');
                    tmplNode.find('.j-view:first').text('请选择笔记本');

                    $animate.enter(tmplNode, element.parent(), element);
                },
                post: function selectSearchPostLink(scope, element, attr, ctrls) {
                    var choseNode = element.next(); //$('#'+attr.name +'_chosecontianer');
                    choseNode.addClass(attr.class);
                    element.addClass('chose-hide');
                    // 当前选中项
                    var ngModelCtrl = ctrls[0];

                    if (!ngModelCtrl || !attr.name) return;

                    parseOptions(attr.ngOptions, element, scope);
                    var rs = {};

                    function setView() {
                        var currentKey = ngModelCtrl.$modelValue;

                        if (isNaN(currentKey) || !currentKey) {
                            currentKey = '';
                            if (choseNode.find('.j-view:first').text() != '请选择笔记本'
                            &&(choseNode.find('.j-view:first').text() != '')
                            ) {
                                swal({
                                    title: '确认删除吗?',
                                    text: '你将会删除笔记本中的所有文件!',
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: '删除!',
                                    cancelButtonText: '取消'
                                }).then(function (isConfirm) {
                                    console.log('notebookid');
                                    console.log(scope.prod.notebookid);
                                    //确认删除
                                    if (isConfirm.value == true) {
                                        $http.get('/api/notebook/delete', {params: {notebook_id: scope.prod.notebookid}})
                                            .then(function (response) {
                                                if (response.data.status) {//删除操作的结果
                                                    for (i = 0; i < scope.notebooks.length; i++) {
                                                        if (scope.notebooks[i].id == scope.prod.notebookid) {
                                                            scope.notebooks.splice(i, 1);
                                                        }
                                                    }
                                                    scope.prod.notebookid = '';
                                                    swal(
                                                        '删除成功',
                                                        '你的笔记本已删除',
                                                        'success'
                                                    );
                                                } else {
                                                    swal(
                                                        '删除失败',
                                                        response.data.msg,
                                                        'error'
                                                    );
                                                }
                                            }), function () {
                                            toastr.error("网络故障，请重试");
                                        }

                                        choseNode.find('.j-view:first').text('请选择笔记本');
                                        choseNode.find('i').addClass('chose-hide');
                                    }
                                });
                            }
                            //显示所有笔记
                            scope.selectUserArticles();
                        }
                        if ((currentKey + '').length > 0) {
                            for (var i = 0, l = rs.idArray.length; i < l; i++) {
                                if (rs.idArray[i] == currentKey) {
                                    choseNode.find('.j-view:first').text(rs.labelArray[i]);
                                    choseNode.find('i').removeClass('chose-hide');

                                    //设置页面笔记本对应的笔记
                                    scope.selectNotebook(currentKey);
                                    scope.prod.notebookid = currentKey;
                                    break;
                                }
                            }
                        }
                    }

                    function setViewAndData() {
                        if (!scope.options) {
                            return;
                        }
                        rs = scope.options;
                        setView();
                    }

                    scope.$watchCollection('options', setViewAndData);
                    scope.$watch(attr.ngModel, setView);

                    function getListNodes(value) {
                        var nodes = [];
                        value = $.trim(value);
                        for (var i = 0, l = rs.labelArray.length; i < l; i++) {
                            if (rs.labelArray[i].indexOf(value) > -1) {
                                nodes.push($('<li>').data('id', rs.idArray[i]).text(rs.labelArray[i]))
                            }
                        }

                        return nodes;

                    }

                    choseNode.on('keyup', '.j-key', function () {
                        // 搜索输入框keyup，重新筛选列表
                        var value = $(this).val();
                        choseNode.find('ul:first').empty().append(getListNodes(value));
                        return false;
                    }).on('click', function () {
                        choseNode.find('.j-drop').removeClass('chose-hide');
                        if (choseNode.find('.j-view:first').text() != '请选择笔记本') {
                            choseNode.find('i').removeClass('chose-hide');
                        }
                        choseNode.find('ul:first').empty().append(getListNodes(choseNode.find('.j-key').val()));
                        return false;
                    }).on('click', 'ul>li', function () {
                        var _this = $(this);
                        ngModelCtrl.$setViewValue(_this.data('id'));
                        ngModelCtrl.$render();
                        choseNode.find('.j-drop').addClass('chose-hide');
                        return false;

                    }).on('click', 'i', function () {
                        ngModelCtrl.$setViewValue('');
                        ngModelCtrl.$render();
                        choseNode.find('.j-view:first').text('请选择笔记本');
                        return false;

                    });
                    $(document).on("click", function () {
                        $('.j-drop').addClass('chose-hide');
                        choseNode.find('i').addClass('chose-hide');
                        return false;
                    });

                }
            }
        };
    });


})();
