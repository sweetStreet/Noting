<!doctype html>
<html ng-app="admin" ng-controller="adminCrtl">
<head>
    <title>Noting</title>
    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <link rel="stylesheet" href="/node_modules/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="/css/admin.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/font-awesome/css/font-awesome.css">
    <link href="/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/node_modules/angular-cookies/angular-cookies.js"></script>
    <script src="/node_modules/sweetalert2/dist/sweetalert2.js"></script>
    <script src="/node_modules/angular-sweetalert-2/dist/angular-sweetalert2.min.js"></script>
    <script type="text/javascript" src="/node_modules/angular-animate/angular-animate.js"></script>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/js/admin.js"></script>
</head>

<body>
<div class ="nav">
    <ul class="nav nav-pills">
        <li role="presentation" ng-class="{ active:'user'== currentType }" >
            <a ui-sref="user" ng-click="userList()">用户管理</a>
        </li>
        <li role="presentation" ng-class="{ active:'log'== currentType }">
            <a ui-sref="log" >日志管理</a>
        </li>
    </ul>
</div>
<div ui-view class="right-part"></div>
</body>

<script type="text/ng-template" id="user.tpl">
    <div><input class="form-control" type="text" ng-model="key" placeholder="请输入姓名关键字"/></div>
    <div>
        <table class="zebra" style="text-align:center">
            <thead>
            <tr>
                <th>用户名</th>
                <th>邮箱</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="x in users | filter:{name:key}">
                <td>[:x.name:]</td>
                <td>[:x.email:]</td>
                <td ng-if="!x.deleted_at">
                    <label class="label_available">可用</label>
                </td>
                <td ng-if="x.deleted_at">
                    <label class="label_unavailable">不可用</label>
                </td>
                <td ng-if="!x.deleted_at">
                    <button class="btn_revise" ng-click="update($index)">修改</button>
                    <button class="btn_delete" ng-click="del($index)">删除</button>
                </td>
                <td ng-if="x.deleted_at">
                    <button class="btn_recovery" ng-click="recover($index)">恢复</button>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- 修改信息 -->
        <div class="modal" id="modal-2">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                        <h3 class="modal-title">修改信息</h3>
                    </div>

                    <div class="modal-body">
                        <div>姓名：</div>
                        <input ng-model="prod.name" value="[:prod.name:]" type="text">
                        <div>年龄：</div>
                        <input ng-model="prod.age" value="[:prod.age:]" type="text">
                        <div>城市：</div>
                        <input ng-model="prod.city" value="[:prod.city:]" type="text">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button class="btn btn-success" ng-click="ensure()">确定</button>
                    </div>

                </div>

            </div>

        </div>
    </div>


    </div>
</script>

<script type="text/ng-template" id="log.tpl">

</script>

<script type="text/javascript">
    //jQuery time
    var parent, ink, d, x, y;
    $(".nav ul li a").click(function(e){
        parent = $(this).parent();
        //create .ink element if it doesn't exist
        if(parent.find(".ink").length == 0)
            parent.prepend("<span class='ink'></span>");

        ink = parent.find(".ink");
        //incase of quick double clicks stop the previous animation
        ink.removeClass("animate");

        //set size of .ink
        if(!ink.height() && !ink.width())
        {
            //use parent's width or height whichever is larger for the diameter to make a circle which can cover the entire element.
            d = Math.max(parent.outerWidth(), parent.outerHeight());
            ink.css({height: d, width: d});
        }

        //get click coordinates
        //logic = click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center;
        x = e.pageX - parent.offset().left - ink.width()/2;
        y = e.pageY - parent.offset().top - ink.height()/2;

        //set the position and add class .animate
        ink.css({top: y+'px', left: x+'px'}).addClass("animate");
    })
</script>

</body>
</html>