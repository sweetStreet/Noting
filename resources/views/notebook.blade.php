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
    <link rel="stylesheet" type="text/css" href="/css/select.css" />
    <link rel="stylesheet" href="/node_modules/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/angular-toastr/dist/angular-toastr.css" />

    <link href="/node_modules/angular-tooltips/dist/angular-tooltips.min.css" rel="stylesheet" type="text/css" />

    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/node_modules/angular-cookies/angular-cookies.js"></script>
    <script src="/js/nav/modernizr.custom.js"></script>
    <script src="/js/nav/classie.js"></script>
    <script src="/js/notebook.js"></script>
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
    <script src="/node_modules/angular-tooltips/dist/angular-tooltips.min.js"></script>
    <!--标签-->
    <script type="text/javascript" src="/node_modules/angularjs-bootstrap-tagsinput/dist/angularjs-bootstrap-tagsinput.js"></script>
    <link rel="stylesheet" type="text/css" href="/node_modules/angularjs-bootstrap-tagsinput/dist/angularjs-bootstrap-tagsinput.css">

</head>
<body class="cbp-spmenu-push" nv-file-drop="" uploader="uploader" filters="queueLimit, customFilter" >
            <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
                <div id="profile">
                    <img id="avator" src="/images/avator.jpeg"/>
                    <button id="btn_edit" ng-click="reviseProfile()"><i class="fa fa-pencil" aria-hidden="true"></i>修改</button>
                    <label id="name">yuki</label>
                </div>

                    <ul class="nav nav-tabs">
                        <li><a  ui-sref="friends" data-toggle="tab">好友列表</a></li>
                        <li><a  ui-sref="file" data-toggle="tab">我的文件</a></li>
                        <li><a  ui-sref="garbage" data-toggle="tab">回收站</a></li>
                    </ul>

                <div ui-view></div>
            </nav>

            <header id="header">
                <!--打开侧边栏-->
<!--                <button id="showLeftPush"><i tooltips tooltip-template="个人中心" class="fa fa-bars fa-2x" aria-hidden="true" ng-click="popLeft()"></i></button>-->
                <button id="btn_add_article"  class="header_button" ng-click="addArticle()"><i tooltips tooltip-template="新建笔记" tooltip-side="bottom" class="fa fa-plus-circle" aria-hidden="true"></i></button>
                <button id="btn_delete_article" class="header_button" ng-click="deleteArticle()"><i tooltips tooltip-template="删除笔记" tooltip-side="bottom"  class="fa fa-trash" aria-hidden="true"></i></button>
                <button id="btn_save_article"class="header_button" ng-click="saveArticle()"><i tooltips tooltip-template="保存笔记" tooltip-side="bottom"  class="fa fa-check-circle" aria-hidden="true"></i></button>
                <button id="btn_tag" class="header_button" ng-click="changeTagFlag()"><i tooltips tooltip-template="标签" tooltip-side="bottom"   class="fa fa-bookmark" aria-hidden="true"></i></button>
                <button id="btn_share" class="header_button"><i tooltips tooltip-template="分享"  tooltip-side="bottom" class="fa fa-user-plus" ng-click="invite()" aria-hidden="true"></i></button>
                <button id="btn_export" class="header_button" pdf-save-button="idOneGraph" pdf-name="noting.pdf"><i tooltips tooltip-template="保存到本地"  tooltip-side="bottom" class="fa fa-download" aria-hidden="true"></i></button>

                <div id="search_article" >
                    <form id="search-form">
                        <div>
                            <input id="search_article" class="form-control" type="text" ng-model="key" placeholder="搜索笔记" ng-change="searchKeyWord()"/>
                        </div>
                    </form>
                </div>
            </header>

<div id="container">
    <ul id="leftnav" class="nav nav-pills nav-stacked">
        <li><a class="showLeftPush" ui-sref="byTags"><i tooltips tooltip-template="搜索标签" tooltip-side="right"  class="fa fa-tag" aria-hidden="true"></i></a></li>
        <li><a class="showLeftPush" ui-sref="file"><i tooltips tooltip-template="我的文件"  tooltip-side="right" class="fa fa-file-text" aria-hidden="true"></i></a></li>
        <li><a class="showLeftPush" ui-sref="profile"><i tooltips tooltip-template="个人信息" tooltip-side="right"  class="fa fa-user" aria-hidden="true"></i></a></li>
    </ul>

    <div id="rightpart">
    <div ng-if = "showtag"
         tagsinput
         tagsinput-id="tagsinputId"
         init-tags="tags"
         onchanged="onTagsChange(data)"
         placeholder="增加一个标签"
    ></div>

    <div id="article" >
        <label>
            <select ngc-select-search class="common-select" ng-model="notebookSelected" ng-options="notebook.id as notebook.title for notebook in notebooks" name="notebook">
                <option value=""></option></select>
            <button id="btn_addnotebook" ng-click="addNotebook()"><i tooltips tooltip-template="新建笔记"  class="fa fa-plus" aria-hidden="true"></i></button>
        </label>

        <div id="article-list">
            <div  ng-repeat="article in articles" ng-click="showInEditor($index)">
            <div class="article_item" name="article_item" ng-to-yellow>
                <p class="article_createTime" ng-bind="article.created_at"></p>
                <p class="article_content"><span ng-bind-html="article.content|htmlContent"></span></p>
            </div>
            <div style="width:100%; height:2px; border-top:1px solid lightgrey; clear:both;"></div>
        </div>
        </div>
    </div>

    <div id="editor">
        <div id="div1" class="toolbar">
        </div>
        <div pdf-save-content="idOneGraph" id="div2" class="text">
            <p>请输入内容</p>
        </div>
    </div>
    </div>

</div>
            <script type="text/javascript" src="/node_modules/wangeditor/release/wangEditor.js"></script>
            <script type="text/javascript" src="/js/article.js"></script>

            <script>
            var showLeftPushs = document.getElementsByClassName( 'showLeftPush' );
            body = document.body;
            menuLeft = document.getElementById( 'cbp-spmenu-s1' );

            console.log('test clicked');

                for (var i = 0; i < showLeftPushs.length; i++) {
                    showLeftPushs[i].onclick = function () {
                        classie.toggle(this, 'active');
                        classie.toggle(body, 'cbp-spmenu-push-toright');
                        classie.toggle(menuLeft, 'cbp-spmenu-open');
                        console.log('hi clicked');
                        console.log(showLeftPushs.length+i);
                }



            </script>


            <script type="text/ng-template" id="profile.tpl">
                <div class="input-group">
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true" style="color:black"></i></button>
                    </span>
                </div><!-- /input-group -->
                <div id="frienditem" ng-repeat="friend in friends">
                    <img id="friend_avator" ng-src=[:friend.imgsrc:] />
                    <label id="friend_email">[:friend.email:]</label>
                    <label id="friend_name">[:friend.name:]</label>
                </div>
            </script>

            <script type="text/ng-template" id="tags.tpl">

            </script>

            <script type="text/ng-template" id="file.tpl">

                <div ng-repeat="photo in photos">
                    <img class="photo" ng-src="[:photo:]" enlarge-pic/>
                </div>

                <!-- 图片放大遮罩层 -->
                <div class="mask" close-pic>
                    <div class="mask-box"></div>
                    <div class="big-pic-wrap">
                        <img src="" alt="" class="bigPic" />
                        <span class="close-pic"><i class="fa fa-close"></i></span>
                    </div>
                </div>
            </script>

</body>
</html>

