(function () {
    'user strict';

    var app = angular.module('notebook',['ui.router','ngCookies','ngCookies','htmlToPdfSave','socialbase.sweetAlert','angularFileUpload']);

    app.config(['$interpolateProvider','$stateProvider','$urlRouterProvider',
        function($interpolateProvider,$stateProvider,$urlRouterProvider) {
            //原本默认的是两个大括号{{内容}}，但是angular和laravel会冲突
            $interpolateProvider.startSymbol("[:");
            $interpolateProvider.endSymbol(":]");

        }
    ]);

    app.controller('notebookCrtl',function($scope,$http,$cookieStore,SweetAlert,FileUploader) {
        //页面初始化
        $scope.init = function () {
            $scope.notebooks = [];
            $scope.notebookSelected = [];
            $scope.articles = [];
            $scope.selectUserNotebooks();
            $scope.selectUserArticles();
        };

            //获得属于某个用户的所有笔记本
            $scope.selectUserNotebooks = function(){
                userid = $cookieStore.get('userid');
                $http.get('/api/notebook/getAll', {
                    params: {
                        userid: userid
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
            }

            //获得属于某个用户的所有文章
            $scope.selectUserArticles = function () {
                userid = $cookieStore.get('userid');
                $http.get('/api/article/getArticlesByUserID', {
                    params: {
                        user_id: userid
                    }
                })
                    .then(function (response) {
                        if (response.data.status) {//有文章
                            $scope.articles = response.data.data;
                            console.log(response.data.data);
                            console.log(response.data.msg);
                        } else {
                            console.log('没有数据');
                        }
                    }), function () {
                    console.log('e');
                }
            }

        //保存文章
        $scope.saveArticle = function () {
            notebookid = $cookieStore.get('notebookid');
            articleid = $cookieStore.get('articleid');
            userid = $cookieStore.get('userid');
            content = editor.txt.html();
            console.log('when save article',notebookid);
            $http.post('/api/article/saveArticle', {
                article_id: articleid,
                user_id: userid,
                notebook_id: notebookid,
                content: content
            })
                .then(function (response) {
                    console.log('when change notebook',notebookid);
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
            $cookieStore.put('notebookid',article.notebook_id);
        }

        //刷新文章列表
        $scope.updateList = function () {
            userid = $cookieStore.get('userid');
            notebookid = $cookieStore.get('notebookid');
            // if (notebookid == '') {
            //     //提示请选择笔记本
            // } else {
                $http.get('/api/article/getArticlesByNotebookID', {
                    params: {
                        user_id: userid,
                        notebook_id: notebookid
                    }
                })
                    .then(function (response) {
                        if (response.data.status) {//获得文章列表
                            $scope.articles = response.data.data;
                            console.log($scope.articles);
                        } else {
                            console.log('error');
                        }
                    }), function () {
                    console.log('e');
                }
            // }
        }

        //新增文章
        $scope.addArticle = function () {
            $editorContent = '<p><br></p>';
            editor.txt.html($editorContent);
            $userid = $cookieStore.get('userid');
            $notebookid = $cookieStore.get('notebookid');
            if($notebookid != null){
                $http.post('/api/article/addArticle', {user_id: $userid, notebook_id: $notebookid, content: $editorContent})
                    .then(function (response) {
                        if (response.data.status) {//创建成功
                            //刷新文章列表
                            $scope.updateList();
                            if($scope.articles!=[]) {
                                $cookieStore.put('articleid', $scope.articles[0].id);
                                console.log('success');
                            }
                        } else {
                            //创建失败
                            console.log('error');
                        }
                    }), function () {
                    console.log('e');
                }
            }else{
                swal({
                    type:'warning',
                    html:'请先选择笔记本'
                })
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
        $scope.selectNotebook = function (notebookid) {
            userid = $cookieStore.get('userid');
            console.log(notebookid);

            $cookieStore.put('notebookid', notebookid);
            $http.get('/api/article/getArticlesByNotebookID', {params: {user_id: userid, notebook_id: notebookid}})
                .then(function (response) {
                    if (response.data.status) {//获得文章列表
                        // if (response.data.data) {
                        //     $scope.articles = response.data.data.data;
                        // }
                        $scope.articles = response.data.data;
                        console.log(response.data.data);
                    } else {
                        console.log('error');
                    }
                }), function () {
                console.log('e');
            }
        }

        //新建笔记本
        $scope.addNotebook=function () {
            userid = $cookieStore.get('userid');
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
                    $userid = $cookieStore.get('userid');

                    $http.post('/api/notebook/create', {userid: userid, title: result.value})
                        .then(function (response) {
                            if (response.data.status) {//创建成功
                                $scope.selectUserNotebooks();
                                $scope.selectNotebook(response.data.notebookid);
                                $cookieStore.put('notebookid',response.data.notebookid);
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

        $scope.shareNotebook=function (notebook) {
            console.log()

        }

        $scope.deleteNotebook=function (notebook) {

        }


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
                            choseNode.find('.j-view:first').text('请选择');
                            choseNode.find('i').addClass('chose-hide');

                            //显示所有笔记
                            scope.selectUserArticles();
                        }
                        if ((currentKey + '').length > 0) {
                            for (var i = 0, l = rs.idArray.length; i < l; i++) {
                                if (rs.idArray[i] == currentKey) {
                                    choseNode.find('.j-view:first').text(rs.labelArray[i]);
                                    choseNode.find('i').removeClass('chose-hide');

                                    //todo: 设置页面笔记本对应的笔记
                                    scope.selectNotebook(currentKey);
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
                        if (choseNode.find('.j-view:first').text() != '请选择') {
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
                        choseNode.find('.j-view:first').text('请选择');
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
