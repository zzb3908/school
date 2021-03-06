<?php /*a:1:{s:77:"E:\phpstudy\PHPTutorial\WWW\webschool\application\manage\view\index\main.html";i:1538959383;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>首页--layui后台管理模板 2.0</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" href="<?php echo config('static_dir'); ?>layui/css/layui.css?_v=<?php echo config('static_ver'); ?>" media="all" />
	<link rel="stylesheet" href="<?php echo config('static_dir'); ?>css/public.css?_v=<?php echo config('static_ver'); ?>" media="all" />
</head>
<body class="childrenBody">
	<blockquote class="layui-elem-quote layui-bg-green">
        <div id="nowTime"></div>
    </blockquote>
    <div class="layui-row layui-col-space10 panel_box">

        <div class="panel layui-col-xs12 layui-col-sm6 layui-col-md4 layui-col-lg2">
            <a href="javascript:;" data-url="/static/manage/page/user/userList.html">
                <div class="panel_icon layui-bg-orange">
                    <i class="layui-anim seraph icon-icon10" data-icon="icon-icon10"></i>
                </div>
                <div class="panel_word userAll">
                    <span></span>
                    <em>用户总数</em>
                    <cite class="layui-hide">用户中心</cite>
                </div>
            </a>
        </div>
        <div class="panel layui-col-xs12 layui-col-sm6 layui-col-md4 layui-col-lg2">
            <a href="javascript:;" data-url="/static/manage/page/systemSetting/icons.html">
                <div class="panel_icon layui-bg-cyan">
                    <i class="layui-anim layui-icon" data-icon="&#xe857;">&#xe857;</i>
                </div>
                <div class="panel_word outIcons">
                    <span></span>
                    <em>外部图标</em>
                    <cite class="layui-hide">图标管理</cite>
                </div>
            </a>
        </div>
        <div class="panel layui-col-xs12 layui-col-sm6 layui-col-md4 layui-col-lg2">
            <a href="javascript:;">
                <div class="panel_icon layui-bg-blue">
                    <i class="layui-anim seraph icon-clock"></i>
                </div>
                <div class="panel_word">
                    <span class="loginTime"></span>
                    <cite>上次登录时间</cite>
                </div>
            </a>
        </div>
    </div>

    <div class="layui-row layui-col-space10">
        <div class="layui-col-lg6 layui-col-md12">
            <blockquote class="layui-elem-quote title">系统基本参数</blockquote>
            <table class="layui-table magt0">
                <colgroup>
                    <col width="150">
                    <col>
                </colgroup>
                <tbody>
                    <tr>
                        <td>当前版本</td>
                        <td class="version"></td>
                    </tr>
                    <tr>
                        <td>开发作者</td>
                        <td class="author"></td>
                    </tr>
                    <tr>
                        <td>网站首页</td>
                        <td class="homePage"></td>
                    </tr>
                    <tr>
                        <td>服务器环境</td>
                        <td class="server"></td>
                    </tr>
                    <tr>
                        <td>数据库版本</td>
                        <td class="dataBase"></td>
                    </tr>
                    <tr>
                        <td>最大上传限制</td>
                        <td class="maxUpload"></td>
                    </tr>
                    <tr>
                        <td>当前用户权限</td>
                        <td class="userRights"></td>
                    </tr>
                </tbody>
            </table>
            <blockquote class="layui-elem-quote title">最新文章 <i class="layui-icon layui-red">&#xe756;</i></blockquote>
            <table class="layui-table mag0" lay-skin="line">
                <colgroup>
                    <col>
                    <col width="110">
                </colgroup>
                <tbody class="hot_news"></tbody>
            </table>
        </div>
        <div class="layui-col-lg6 layui-col-md12">
            <blockquote class="layui-elem-quote title">阶段&更新日志</blockquote>
            <div class="layui-elem-quote layui-quote-nm history_box magb0">
                <ul class="layui-timeline">
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis">&#xe756;</i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">
                                <h3 class="layui-inline">象牙塔-后台管理版本:<span class="layui-red"> ver.1.0基础版</span> 发布　</h3>
                                <span class="layui-badge-rim">2018-07-20</span>
                            </div>
                            <ul>

                                <li>优化刷新当前页面，关闭其他，关闭全部等按钮造成的bug</li>

                                <li>对90%以上的页面进行了样式优化和微调，使其更加完美【对于有强迫症的我来说，有一点瑕疵都是不能容忍的】</li>

                            </ul>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>

	<script type="text/javascript" src="<?php echo config('static_dir'); ?>layui/layui.js?_v=<?php echo config('static_ver'); ?>"></script>
	<script type="text/javascript" src="<?php echo config('static_dir'); ?>js/main.js?_v=<?php echo config('static_ver'); ?>"></script>
</body>
</html>