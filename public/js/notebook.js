(function () {
    'user strict';

    var app = angular.module('notebook',['ui.router','ngCookies','htmlToPdfSave','socialbase.sweetAlert','angularFileUpload','ngAnimate', 'toastr','akoenig.deckgrid']);

    app.config(['$interpolateProvider','$stateProvider','$urlRouterProvider',
        function($interpolateProvider,$stateProvider,$urlRouterProvider) {
            //原本默认的是两个大括号{{内容}}，但是angular和laravel会冲突
            $interpolateProvider.startSymbol("[:");
            $interpolateProvider.endSymbol(":]");

        }
    ]);

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
                console.log('when save article', notebookid);
                $http.post('/api/article/saveArticle', {
                    article_id: articleid,
                    user_id: userid,
                    notebook_id: notebookid,
                    content: content
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

        $scope.shareNotebook=function (notebook) {
            console.log()

        }



        $scope.deleteNotebook=function (notebook) {

        }



        $scope.popLeft = function(){
            $scope.photos = [{
                src: "https://d2dcan0armyq93.cloudfront.net/photo/odai/600/abf9b57d34338cdd95de59fe6903e3fe_600.jpg",
                link: "http://bokete.jp/boke/39978078",
                title: "とぼけんな、給料日だろ"
            }, {
                src: "https://d2dcan0armyq93.cloudfront.net/photo/odai/600/904a534a5dba72ea5eb879182441cd16_600.jpg",
                link: "http://bokete.jp/boke/39979574",
                title: "奈落より　出でよ破壊の"
            }, {
                src: "https://d2dcan0armyq93.cloudfront.net/photo/odai/600/81d5a932257f2cb36dc6e56b7d1e1fa4_600.jpg",
                link: "http://bokete.jp/boke/39972987",
                title: "職質かけたら思いっきりグーパンされたので一旦部下のとこへ戻る"
            }, {
                src: "https://d2dcan0armyq93.cloudfront.net/photo/odai/600/09ea4d769e819f473a2b538860a94c9e_600.jpg",
                link: "http://bokete.jp/boke/39970408",
                title: "ヘディングしてから様子がおかしい"
            }, {
                src: "https://d2dcan0armyq93.cloudfront.net/photo/odai/600/ef164c0fa2b42aac8e6cb512811cbed0_600.jpg",
                link: "http://bokete.jp/boke/39964824",
                title: "けえ"
            }, {
                src: "https://d2dcan0armyq93.cloudfront.net/photo/odai/600/07af9fc488bb67250a5f5dc69cea9bae_600.jpg",
                link: "http://bokete.jp/boke/39953927",
                title: "大佐が掃除機かけ始めた。"
            }, {
                src: "https://d2dcan0armyq93.cloudfront.net/photo/odai/600/92a0c960ca925b7cf8fc714a16463d23_600.jpg",
                link: "http://bokete.jp/boke/39980792",
                title: "占い師に「今年２月に人生で最高についてることが起こります」って言われてたけどコレじゃないことを全力で祈る"
            }, {
                src: "https://d2dcan0armyq93.cloudfront.net/photo/odai/600/4e11f7ba4767d995053442bb3a98c0ab_600.jpg",
                link: "http://bokete.jp/boke/39967369",
                title: "四年も経って"
            }, {
                src: "https://d2dcan0armyq93.cloudfront.net/photo/odai/600/ea01d23c4713273be387d58d611331ac_600.jpg",
                link: "http://bokete.jp/boke/39998749",
                title: "這ってるヤツと中腰のヤツがやたら早い"
            }, {
                src: "https://d2dcan0armyq93.cloudfront.net/photo/odai/600/ac25d96925da8be29b71724ca780491c_600.jpg",
                link: "http://bokete.jp/boke/39973578",
                title: "銀のエンゼルがなかなか当たらないので直接狩りにきたら結構強かった"
            }]
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
