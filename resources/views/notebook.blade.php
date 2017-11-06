<!doctype html>
<html ng-app="notebook" ng-controller="notebookCrtl" ng-init="init()">
<head>
    <meta name="csrf-token" content="[:csrf_token():]">
    <title>notebook</title>

    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>

    <link href="/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="/node_modules/angular-bootstrap-multiselect/dist/angular-bootstrap-multiselect.js"></script>

    <script src="/js/notebook.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/notebook.css">

    <script src="/node_modules/angular-cookies/angular-cookies.js"></script>

    <!--左侧导航栏-->
    <link rel="stylesheet" type="text/css" href="/css/nav/component.css" />
    <script src="/js/nav/modernizr.custom.js"></script>
    <script src="/js/nav/classie.js"></script>

    <!--font awesome-->
<!--    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css"/>

    <link rel="stylesheet" type="text/css" href="/css/article.css">

</head>
<body class="cbp-spmenu-push">
            <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
                <h3>Menu</h3>
                <a href="http://www.htmleaf.com/" target="_blank">Celery seakale</a>
                <a href="http://www.htmleaf.com/" target="_blank">Dulse daikon</a>
                <a href="http://www.htmleaf.com/" target="_blank">Zucchini garlic</a>
                <a href="http://www.htmleaf.com/" target="_blank">Catsear azuki bean</a>
                <a href="http://www.htmleaf.com/" target="_blank">Dandelion bunya</a>
                <a href="http://www.htmleaf.com/" target="_blank">Rutabaga</a>
            </nav>

            <!--打开侧边栏-->
            <!-- Class "cbp-spmenu-open" gets applied to menu and "cbp-spmenu-push-toleft" or "cbp-spmenu-push-toright" to the body -->
            <button id="showLeftPush"><i class="fa fa-bars fa-2x" aria-hidden="true"></i></button>

    <div id="container">
        <div id = "notebook_nav">
            <!--快速选择笔记本和笔记-->
            <button id="addNotebook"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i>新增笔记本</button>


            <div id="notebook-item">
                    <multiselect ng-model="notebookSelected" options="notebooks" id-prop="id"
                                 display-prop="title" show-search="true" selection-limit="1"
                                 placeholder="选择一本笔记本" classes-btn="'btn-primary btn-block'"
                    >
                    </multiselect>

                    <div>选中的内容 [: notebookSelected[0].title :]</div>

<!--                    <div>-->
<!--                    <table>-->
<!--                        <tr ng-repeat="notebook in notebooks">-->
<!--                            <td>[: notebook.id :]</td>-->
<!--                            <td>[: notebook.title :]</td>-->
<!--                        </tr>-->
<!--                    </table>-->
<!--                    </div>-->
            </div>

            <div id="search_article">
                <form id="search-form">
                    <input type="text"><button><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>
            </div>

        <div id="article">
            <div ng-repeat="notebook in notebooks | filter:query | orderBy:order">
                <div class="article_item">
                    <p class="article_createTime">[: notebook.id :]</p>
                    <p class="article_content">[: notebook.title :]</p>
                    <p>需要显示的文本：[:html:]</p>
                    <p>Html格式化文本： <span ng-bind-html="html|htmlContent"></span></p>

                </div>
            </div>
        </div>

        </div>
        <div id = "editor">
            <div id="div1" class="toolbar">
            </div>
            <div style="padding: 5px 0; color: #ccc"></div>
            <div id="div2" class="text">
                <p>第一个 demo（菜单和编辑器区域分开）</p>
            </div>
            <button id="btn1" ng-click="saveArticle()">保存</button>
        </div>

    </div>


    <!-- 注意， 只需要引用 JS，无需引用任何 CSS ！！！-->
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