<!doctype html>
<html ng-app="notebook" ng-controller="notebookCrtl" ng-init="init()">
<head>
    <title>notebook</title>

    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <link href="/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--左侧导航栏-->
    <link rel="stylesheet" type="text/css" href="/css/nav/component.css" />
    <!--font awesome-->
    <link rel="stylesheet" type="text/css" href="/node_modules/font-awesome/css/font-awesome.css">
    <!--notebook的css-->
    <link rel="stylesheet" type="text/css" href="/css/notebook.css">
    <!--笔记-->
    <link rel="stylesheet" type="text/css" href="/css/book/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/css/book/book2.css" />
    <link rel="stylesheet" type="text/css" href="/css/select.css" />
    <link rel="stylesheet" href="/node_modules/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/angular-toastr/dist/angular-toastr.css" />

</head>
<body class="cbp-spmenu-push" nv-file-drop="" uploader="uploader" filters="queueLimit, customFilter">
            <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
                <p id="mydesk">我的文件</p>
                <input id="fileId1" type="file" accept="image/png,image/gif" name="file" />
            </nav>

            <header id="header">
                <!--打开侧边栏-->
                <!-- class "cbp-spmenu-open" gets applied to menu and "cbp-spmenu-push-toleft" or "cbp-spmenu-push-toright" to the body -->
                <button id="showLeftPush"><i class="fa fa-book fa-2x" aria-hidden="true"></i></button>
                <button id="btn_add_article" class="header_button" ng-click="addArticle()"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                <button id="btn_delete_article" class="header_button" ng-click="deleteArticle()"><i class="fa fa-trash" aria-hidden="true"></i></button>
                <button id="btn_save_article"class="header_button" ng-click="saveArticle()"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
                <button id="btn_tag" class="header_button"><i class="fa fa-bookmark" aria-hidden="true"></i></button>
<!--                <button id="btn_share" class="header_button"></button>-->
                <button id="btn_export" class="header_button" pdf-save-button="idOneGraph" pdf-name="hello.pdf" "><i class="fa fa-download" aria-hidden="true"></i></button>

                <div id="search_article" >
                    <form id="search-form">
                        <input id="input_search_article" type="text" value="搜索笔记"
                               onfocus="if (value =='搜索笔记'){value =''}"onblur="if (value ==''){value='搜索笔记'}"/>><button id="btn_search_article"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>
                </div>
            </header>

<div id="container">
    <hr/>
    <div id="article" >
        <label>
            <select ngc-select-search class="common-select" ng-model="notebookSelected" ng-options="notebook.id as notebook.title for notebook in notebooks" name="notebook">
                <option value="">请选择</option></select>
            <button id="btn_addnotebook" ng-click="addNotebook()"><i class="fa fa-plus" aria-hidden="true"></i></button>
        </label>

        <div ng-repeat="article in articles" ng-click="showInEditor($index)">
            <div class="article_item" name="article_item" ng-to-yellow>
                <p class="article_createTime">[: article.created_at :]</p>
                <p class="article_content"><span ng-bind-html="article.content|htmlContent"></span></p>
            </div>
            <div style="width:100%; height:2px; border-top:1px solid lightgrey; clear:both;"></div>
        </div>
    </div>

    <div id="editor">
        <div id="div1" class="toolbar">
        </div>
        <div pdf-save-content="idOneGraph" id="div2" class="text">
            <p>请输入内容</p>
        </div>
    </div>


    <div class="row">
        <h6>文件</h6>
            <div ng-show="uploader.isHTML5">
                <div class="well my-drop-zone" nv-file-over="" uploader="uploader">
                    Base drop zone
                </div>
<!--                <!-- Example: nv-file-drop="" uploader="{Object}" options="{Object}" filters="{String}" -->
<!--                <div nv-file-drop="" uploader="uploader" options="{ url: '/foo' }">-->
<!--                    <div nv-file-over="" uploader="uploader" over-class="another-file-over-class" class="well my-drop-zone">-->
<!--                        Another drop zone with its own settings-->
<!--                    </div>-->
<!--                </div>-->
        </div>

        <div class="col-md-9" style="margin-bottom: 40px">

            <h3>Upload queue</h3>
            <p>Queue length: [: uploader.queue.length :]</p>

            <table class="table">
                <thead>
                <tr>
                    <th width="50%">Name</th>
                    <th ng-show="uploader.isHTML5">Size</th>
                    <th ng-show="uploader.isHTML5">Progress</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="item in uploader.queue">
                    <td><strong>[: item.file.name :]</strong></td>
                    <td ng-show="uploader.isHTML5" nowrap>[: item.file.size/1024/1024|number:2 :] MB</td>
                    <td ng-show="uploader.isHTML5">
                        <div class="progress" style="margin-bottom: 0;">
                            <div class="progress-bar" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
                        <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
                        <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
                    </td>
                    <td nowrap>
                        <button type="button" class="btn btn-success btn-xs" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                            <span class="glyphicon glyphicon-upload"></span> Upload
                        </button>
                        <button type="button" class="btn btn-warning btn-xs" ng-click="item.cancel()" ng-disabled="!item.isUploading">
                            <span class="glyphicon glyphicon-ban-circle"></span> Cancel
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()">
                            <span class="glyphicon glyphicon-trash"></span> Remove
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div>
                <div>
                    Queue progress:
                    <div class="progress" style="">
                        <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.progress + '%' }"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-s" ng-click="uploader.uploadAll()" ng-disabled="!uploader.getNotUploadedItems().length">
                    <span class="glyphicon glyphicon-upload"></span> Upload all
                </button>
                <button type="button" class="btn btn-warning btn-s" ng-click="uploader.cancelAll()" ng-disabled="!uploader.isUploading">
                    <span class="glyphicon glyphicon-ban-circle"></span> Cancel all
                </button>
                <button type="button" class="btn btn-danger btn-s" ng-click="uploader.clearQueue()" ng-disabled="!uploader.queue.length">
                    <span class="glyphicon glyphicon-trash"></span> Remove all
                </button>
            </div>

        </div>

    </div>


</div>
            <script type="text/javascript" src="/node_modules/wangeditor/release/wangEditor.js"></script>
            <script type="text/javascript" src="/js/article.js"></script>

            <script>
            var showLeftPush = document.getElementById( 'showLeftPush' ),
            body = document.body;
            menuLeft = document.getElementById( 'cbp-spmenu-s1' ),

            showLeftPush.onclick = function() {
                    classie.toggle( this, 'active' );
                    classie.toggle( body, 'cbp-spmenu-push-toright' );
                    classie.toggle( menuLeft, 'cbp-spmenu-open' );
            };

    </script>
            <script src="/node_modules/jquery/dist/jquery.js"></script>
            <script src="/node_modules/angular/angular.js"></script>
            <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
            <script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
            <script src="/node_modules/angular-cookies/angular-cookies.js"></script>
            <script src="/js/nav/modernizr.custom.js"></script>
            <script src="/js/nav/classie.js"></script>
            <script src="/js/notebook.js"></script>
            <script src="/js/book/modernizr.custom.js"></script>
            <script src="/node_modules/sweetalert2/dist/sweetalert2.js"></script>
            <script src="/node_modules/angular-sweetalert-2/dist/angular-sweetalert2.min.js"></script>
            <!--上传文件-->
            <script src="/node_modules/angular-file-upload/dist/angular-file-upload.js"></script>
            <!--转成pdf格式-->
            <script src="/js/topdf/html2canvas.js"></script>
            <script src="/js/topdf/jspdf.debug.js"></script>
            <script type="text/javascript" src="/node_modules/angular-save-html-to-pdf/dist/saveHtmlToPdf.js"></script>
            <!--提示-->
            <script type="text/javascript" src="/node_modules/angular-animate/angular-animate.js"></script>
            <script type="text/javascript" src="/node_modules/angular-toastr/dist/angular-toastr.tpls.js"></script>

</body>
</html>

