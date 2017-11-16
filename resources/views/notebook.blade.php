<!doctype html>
<html ng-app="notebook" ng-controller="notebookCrtl" ng-init="init()">
<head>
    <title>notebook</title>

    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>

    <link href="/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="/node_modules/angular-bootstrap-multiselect/dist/angular-bootstrap-multiselect.js"></script>

    <script src="/node_modules/angular-cookies/angular-cookies.js"></script>

    <!--左侧导航栏-->
    <link rel="stylesheet" type="text/css" href="/css/nav/component.css" />
    <script src="/js/nav/modernizr.custom.js"></script>
    <script src="/js/nav/classie.js"></script>
<!--    <link rel="stylesheet" type="text/css" href="/node_modules/wangeditor/release/wangEditor.css">-->
    <!--font awesome-->
<!--    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css"/>

    <!--转成pdf格式-->
    <script src="/js/topdf/html2canvas.js"></script>
    <script src="/js/topdf/jspdf.debug.js"></script>
    <script type="text/javascript" src="/node_modules/angular-save-html-to-pdf/dist/saveHtmlToPdf.js"></script>
    <!--notebook的js和css-->
    <script src="/js/notebook.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/notebook.css">
    <!--笔记-->
    <link rel="stylesheet" type="text/css" href="/css/book/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/css/book/demo.css" />
    <link rel="stylesheet" type="text/css" href="/css/book/book2.css" />
    <script src="/js/book/modernizr.custom.js"></script>

</head>
<body class="cbp-spmenu-push">
            <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">

                <a href="/api/user"><i class="fa fa-user-o fa-3x" aria-hidden="true"></i>我的主页</a>
                <div id = "notebook_nav">
                    <!--快速选择笔记本和笔记-->
                    <button id="addNotebook"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i></button>

<!--                    <multiselect ng-model="notebookSelected" options="notebooks" id-prop="id"-->
<!--                                 display-prop="title" show-search="true" selection-limit="1"-->
<!--                                 placeholder="选择一本笔记本""-->
<!--                    >-->
<!--                    </multiselect>-->
                    <div id="notebook-item">
                        <li>
                            <figure class='book'>

                                <!-- Front -->

                                <ul class='paperback_front'>
                                    <li>
                                        <span class="ribbon">NEW</span>
                                        <img src="/images/paper.jpg" alt="" width="100%" height="100%">
                                    </li>
                                    <li></li>
                                </ul>

                                <!-- Pages -->

                                <ul class='ruled_paper'>
                                    <li></li>
                                    <li>
                                        <a class="btn" href="http://www.codehero.top">PREVIEW</a>
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
                                    <h1>绘制</h1>
                                    <span>By 代码侠</span>
                                    <p>代码侠是一个分享酷站的网站，这里有很多炫酷的效果展示，也有教程，玩代码，找代码侠。</p>
                                </figcaption>
                            </figure>
                        </li>

                        <div>选中的内容 [: notebookSelected[0].title :]</div>
                    </div>
                </div

                <div id="article" >
                    <div ng-repeat="article in articles" ng-click="showInEditor(article)">
                        <div class="article_item" name="article_item" ng-to-yellow>
                            <p class="article_createTime">[: article.created_at :]</p>
                            <p class="article_content"><span ng-bind-html="article.content|htmlContent"></span></p>
                        </div>
                    </div>
                </div>
            </nav>

            <header id="header">
                <!--打开侧边栏-->
                <!-- class "cbp-spmenu-open" gets applied to menu and "cbp-spmenu-push-toleft" or "cbp-spmenu-push-toright" to the body -->
                <button id="showLeftPush"><i class="fa fa-bars fa-2x" aria-hidden="true"></i></button>
                <button id="btn_add_article" class="header_button" ng-click="addArticle()"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                <button id="btn_delete_article" class="header_button" ng-click="deleteArticle()"><i class="fa fa-trash" aria-hidden="true"></i></button>
                <button id="btn_save_article"class="header_button" ng-click="saveArticle()"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
                <button id="btn_tag" class="header_button"><i class="fa fa-bookmark" aria-hidden="true"></i></button>
                <button id="btn_share" class="header_button"><i class="fa fa-user-plus" aria-hidden="true"></i></button>
                <button id="btn_export" class="header_button" pdf-save-button="idOneGraph" pdf-name="hello.pdf" "><i class="fa fa-download" aria-hidden="true"></i></button>

                <div id="search_article" >
                    <form id="search-form">
                        <input id="input_search_article" type="text" value="搜索笔记"
                               onfocus="if (value =='搜索笔记'){value =''}"onblur="if (value ==''){value='搜索笔记'}"/>><button id="btn_search_article"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>
                </div>
            </header>

    <div id="editor">
        <div id="div1" class="toolbar">
        </div>
        <div pdf-save-content="idOneGraph" id="div2" class="text">
            <p>请输入内容</p>
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
</body>
</html>