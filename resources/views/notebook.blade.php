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
    <link rel="stylesheet" type="text/css" href="/node_modules/angularjs-bootstrap-tagsinput/dist/angularjs-bootstrap-tagsinput.css">
    <link href="/node_modules/angular-tooltips/dist/angular-tooltips.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/node_modules/angucomplete-alt/angucomplete-alt.css"/>

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
    <!--自动补全-->
    <script type="text/javascript" src="/node_modules/angucomplete-alt/angucomplete-alt.js"></script>

</head>
<body class="cbp-spmenu-push" nv-file-drop="" uploader="uploader" filters="queueLimit, customFilter" >
            <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
                <button id="hideLeftPush"><i tooltips tooltip-template="关闭" tooltip-side="bottom" class="fa fa-times fa-2x" aria-hidden="true"></i></button>
                <div ui-view></div>
            </nav>

            <header id="header">
                <button id="btn_add_article"  class="header_button" ng-click="addArticle()"><i tooltips tooltip-template="新建笔记" tooltip-side="bottom" class="fa fa-plus-circle" aria-hidden="true"></i></button>
                <button id="btn_delete_article" class="header_button" ng-click="deleteArticle()"><i tooltips tooltip-template="删除笔记" tooltip-side="bottom"  class="fa fa-trash" aria-hidden="true"></i></button>
                <button id="btn_save_article"class="header_button" ng-click="saveArticle()"><i tooltips tooltip-template="保存笔记" tooltip-side="bottom"  class="fa fa-check-circle" aria-hidden="true"></i></button>
                <button id="btn_tag" class="header_button" ng-click="changeTagFlag()"><i tooltips tooltip-template="标签" tooltip-side="bottom"   class="fa fa-bookmark" aria-hidden="true"></i></button>
                <button id="btn_share" class="header_button" ng-click="changeShareFlag()"><i tooltips tooltip-template="分享"  tooltip-side="bottom" class="fa fa-user-plus" aria-hidden="true"></i></button>
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
        <li><a class="showLeftPush" ui-sref="byTags" ng-click="getTags()"><i tooltips tooltip-template="搜索标签" tooltip-side="right"  class="fa fa-tag" aria-hidden="true"></i></a></li>
        <li><a class="showLeftPush" ui-sref="file" ng-click="getFile()"><i tooltips tooltip-template="我的文件"  tooltip-side="right" class="fa fa-file-text" aria-hidden="true"></i></a></li>
        <li><a class="showLeftPush" ui-sref="profile" ng-click="getNotification()"><i tooltips tooltip-template="个人信息" tooltip-side="right"  class="fa fa-user" aria-hidden="true"></i></a></li>
    </ul>

    <div id="rightpart">
    <div ng-if = "showtag"
         tagsinput
         tagsinput-id="tagsinputId"
         init-tags="tags"
         onchanged="onTagsChange(data)"
         placeholder="增加一个标签"
    ></div>

        <div class="padded-row" ng-if="showShare" style="margin-bottom: 10px">
            <div angucomplete-alt
                 id="ex2"
                 placeholder="输入对方邮箱/用户名"
                 pause="300"
                 selected-object="selectedPersonFn"
                 local-data="userList"
                 title-field="email"
                 description-field="name"
                 image-field="pic"
                 minlength="1"
                 input-class="form-control form-control-small"
                 match-class="highlight"
                 local-search="localSearch"
                 initial-value="selectedPerson"
                 >
            </div>

            <button class="btn btn-default" type="button" ng-click="shareConfirmed()">
                <i class="fa fa-check" aria-hidden="true" style="color:black"></i>确认发送</button>
        </div>


    <div id="article" >
        <label>
            <select ngc-select-search class="common-select" ng-model="notebookSelected" ng-options="notebook.id as notebook.title for notebook in notebooks" name="notebook">
                <option ></option></select>
<!--            <sapn>-->
                <button id="btn_addnotebook" ng-click="addNotebook()"><i tooltips tooltip-template="新建笔记本"  class="fa fa-plus" aria-hidden="true"></i></button>
<!--                <button id="btn_addnotebook" ng-click="changeNotebook()"><i tooltips tooltip-template="修改笔记本"  class="fa fa-pencil" aria-hidden="true"></i></button>-->
<!--            </sapn>-->
        </label>

        <div id="article-list">
            <div  ng-repeat="article in articles track by $index" ng-click="showInEditor($index)">
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
                        classie.add(this, 'active');
                        classie.add(body, 'cbp-spmenu-push-toright');
                        classie.add(menuLeft, 'cbp-spmenu-open');
                    }
                }

            var back = document.getElementById('hideLeftPush');
                back.onclick = function(){
                    classie.remove(this, 'active');
                    classie.remove(body, 'cbp-spmenu-push-toright');
                    classie.remove(menuLeft, 'cbp-spmenu-open');
                }

            </script>

<!--            个人信息-->
            <script type="text/ng-template" id="profile.tpl">
                <div id="profile" style="margin-top:10px">
                    <img id="avator" src="/images/avator.jpeg"/>
                </div>

                <form class="form-horizontal" role="form" style="color:white; padding-top: 2%;">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">邮箱</label>
                        <div class="col-sm-8">
                            <p ng-bind="user.email" class="form-control-static" style="align-self: center"></p>
                        </div>
                        <label class="col-sm-3 control-label">用户名</label>
                        <div class="col-sm-8">
                            <p ng-bind="user.name" class="form-control-static"></p>
                        </div>
                    </div>
                </form>
                <button id="btn_edit" ng-click="reviseUsername()"><i class="fa fa-pencil" aria-hidden="true"></i>修改用户名</button>
                <button id="btn_edit" ng-click="revisePassword()"><i class="fa fa-pencil" aria-hidden="true"></i>修改密码</button>

                <div ng-repeat="notification in notifications">
                    <label ng-click="showNotification = !showNotification">[:notification.from_user_name:]<[:notification.from_user_email:]>分享了一份笔记给你</label>
                    <span ng-show="showNotification" ng-bind-html="notification.content|htmlContent"></span>
                </div>


            </script>

<!--  根据标签搜索-->
            <script type="text/ng-template" id="tags.tpl">
                <div class="input-group" style="margin-top: 10%">
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true" style="color:black"></i></button>
                    </span>
                </div>

                <div ng-repeat="tagSearch in tagsSearch">
                    <button type="button" class="btn btn-warning" ng-click="searchByTag(tagSearch.id)">[:tagSearch.name:]</button>
                </div>
            </script>

<!--查看文件-->
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


            <script type="text/ng-template" id="friends.tpl">
                <div id="frienditem" ng-repeat="friend in friends">
                    <img id="friend_avator" ng-src=[:friend.imgsrc:] />
                    <label id="friend_email">[:friend.email:]</label>
                    <label id="friend_name">[:friend.name:]</label>
                </div>
            </script>

</body>
</html>

