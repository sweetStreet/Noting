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

</head>
<body class="cbp-spmenu-push">
            <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
                <p id="mydesk">我的书桌</p>
                <div id = "notebook_nav">
                    <!--快速选择笔记本和笔记-->
                    <div class="search">
                        <div class="bar7">
                            <form>
                                <input type="text" placeholder="请输入笔记本名字...">
                                <button id="btn_bar7" type="submit"></button>
                            </form>
                            <button id="btn_addnotebook" ng-click="addNotebook()"><i class="fa fa-plus" aria-hidden="true"></i>新增笔记本</button>
                        </div>
                    </div>


                    <ul id="notebook-list" ng-repeat="notebook in notebooks">
                        <li>
                            <figure class='book'>

                                <!-- Front -->

                                <ul class='paperback_front'>
                                    <li>
                                        <span class="ribbon">[: notebook.created_at.substring(5,10) :]</span>
                                        <img src="/images/paper.jpg" alt="" width="100%" height="100%">
                                    </li>
                                    <li></li>
                                </ul>

                                <!-- Pages -->

                                <ul class='ruled_paper'>
                                    <li></li>
                                    <li>
                                        <a class="btn" ng-click="selectNotebook(notebook)">进入笔记本</a>
                                    </li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                </ul>

                                <!-- Back -->

                                <ul class='paperback_back'>
                                    <li>
                                        <img src="/images/paper.jpg" alt="" width="100%" height="100%">
                                    </li>
                                    <li></li>
                                </ul>
                                <figcaption>
                                    <h1>[: notebook.title :]</h1>
                                    <span>By yuki</span>
                                    <button ng-click="shareNotebook(notebook)"><i class="fa fa-user-plus" aria-hidden="true"></i></button>
                                    <button ng-click="deleteNotebook(notebook)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </figcaption>
                            </figure>
                        </li>


                    </ul>
                </div>
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
            <select ngc-select-search class="common-select" ng-model="notebookSelected" ng-options="notebook.id as notebook.title for notebook in notebooks" name="notebookSelected"  >
                <option value="notebook">请选择笔记本</option></select>
            <button id="btn_addnotebook" ng-click="addNotebook()"><i class="fa fa-plus" aria-hidden="true"></i></button>
        </label>

        <div ng-repeat="article in articles" ng-click="showInEditor(article)">
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
            <!--转成pdf格式-->
            <script src="/js/topdf/html2canvas.js"></script>
            <script src="/js/topdf/jspdf.debug.js"></script>
            <script type="text/javascript" src="/node_modules/angular-save-html-to-pdf/dist/saveHtmlToPdf.js"></script>
</body>
</html>

