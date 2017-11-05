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

    <script src="/js/navcustom.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/nav/navstyle.css">
    <link rel="stylesheet" type="text/css" href="/css/nav/default.css">
    <link rel="stylesheet" type="text/css" href="/css/nav/normalize.css">
    <link rel="stylesheet" type="text/css" href="/css/nav/reset.css">

    <script src="/js/notebook.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/notebook.css">

    <script src="/js/article.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/article.css">

    <script src="/node_modules/angular-cookies/angular-cookies.js"></script>
</head>
<body>
    <div id="container">
        <div id="canvas">
            <div id="nav">
                <h2 id="title"><i class="fa fa-sitemap"></i> MENU</h2>
                <ul id="toggle">
                    <li>
                        <div class="active border">
                            <span class="menu-icons fa fa-home"></span>   <a href="#">HOME</a>
                        </div>
                    </li>
                    <li>
                        <div>
                            <span class="menu-icons  fa fa-user"></span>   <a href="#">ABOUT US</a>
                            <span class="the-btn fa fa-plus"></span>
                        </div>
                        <ul>
                            <li>
                                <a href="#">OUR TEAM</a>
                            </li>
                            <li>
                                <a href="#">OUR SERVICES</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div>
                            <span class="menu-icons  fa fa-briefcase"></span>
                            <a href="#">PORTFOLIO</a><span class="the-btn fa fa-plus"></span>
                        </div>
                        <ul>
                            <li>
                                <a href="#">WEB DESIGN</a>
                            </li>

                            <li>
                                <a href="#">GRAPHIC DESIGN</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div>
                            <span class="menu-icons  fa fa-envelope"></span>
                            <a href="#">CONTACT</a>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="htmleaf-container">
                <div class="notebook-item">
                    <multiselect ng-model="notebookSelected" options="notebooks" id-prop="id"
                                 display-prop="title" show-search="true" selection-limit="1"
                                 placeholder="选择一本笔记本" classes-btn="'btn-primary btn-block'"
                    >
                    </multiselect>

                    <div>选中的内容 [: notebookSelected[0].title :]</div>

                    <div>
                    <table>
                        <tr ng-repeat="notebook in notebooks">
                            <td>[: notebook.id :]</td>
                            <td>[: notebook.title :]</td>
                        </tr>
                    </table>
                    </div>
                </div>


                <div class = "editor">
                    <div id="div1" class="toolbar">
                    </div>
                    <div style="padding: 5px 0; color: #ccc"></div>
                    <div id="div2" class="text">
                        <p>第一个 demo（菜单和编辑器区域分开）</p>
                    </div>

                    <button id="btn1">获取html</button>
                    <button id="btn2">获取text</button>
                </div>
            </div>
        </div>
    </div>



    <!-- 注意， 只需要引用 JS，无需引用任何 CSS ！！！-->
    <script type="text/javascript" src="/node_modules/wangeditor/release/wangEditor.js"></script>
    <script type="text/javascript">
        var E = window.wangEditor
        var editor = new E('#div1', '#div2')
        editor.customConfig.uploadImgServer = '/upload'  // 上传图片到服务器
        editor.customConfig.linkImgCallback = function (url) {
            console.log(url) // url 即插入图片的地址
        }
        //TODO 配置debug模式 记得结束之后删除
        editor.customConfig.debug = true

        editor.customConfig.onchange = function (html) {
            // html 即变化之后的内容
            console.log(html)
        }
        // 自定义 onchange 触发的延迟时间，默认为 200 ms
        editor.customConfig.onchangeTimeout = 1000 // 单位 ms

        editor.customConfig.linkCheck = function (text, link) {
            console.log(text) // 插入的文字
            console.log(link) // 插入的链接

            return true // 返回 true 表示校验成功
            // return '验证失败' // 返回字符串，即校验失败的提示信息
        }

        editor.create()

        document.getElementById('btn1').addEventListener('click', function () {
            // 读取 html
            alert(editor.txt.html())
        }, false)

        document.getElementById('btn2').addEventListener('click', function () {
            // 读取 text
            alert(editor.txt.text())
        }, false)
    </script>

</body>
</html>