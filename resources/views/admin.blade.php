<!doctype html>
<html ng-app="admin" ng-controller="adminCrtl">
<head>
    <title>Noting</title>
    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <link rel="stylesheet" href="/node_modules/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="/css/admin.css">

    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/node_modules/angular-cookies/angular-cookies.js"></script>
    <script src="/node_modules/sweetalert2/dist/sweetalert2.js"></script>
    <script src="/node_modules/angular-sweetalert-2/dist/angular-sweetalert2.min.js"></script>
    <script src="/js/admin.js"></script>
</head>

<body>
<div class ="nav">
    <ul class="nav nav-pills">
        <li role="presentation" ng-class="{ active:'user'== currentType }" >
            <a ui-sref="user">用户管理</a>
        </li>
        <li role="presentation" ng-class="{ active:'log'== currentType }">
            <a ui-sref="log">日志管理</a>
        </li>
    </ul>
</div>
<div ui-view class="right-part"></div>
</body>

<script type="text/ng-template" id="user.tpl">
    <div>
        刘鱼鱼
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