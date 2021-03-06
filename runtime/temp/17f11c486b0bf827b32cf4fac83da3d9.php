<?php /*a:1:{s:79:"E:\phpstudy\PHPTutorial\WWW\webschool\application\manage\view\showme\index.html";i:1538959383;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>秀吧管理</title>
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
<form class="layui-form">
    <blockquote class="layui-elem-quote quoteBox">
        <form class="layui-form">
            <div class="layui-inline">
                <a class="layui-btn layui-btn-normal addNews_btn">添加秀吧</a>
            </div>
            <div class="layui-inline" style="margin-right:10px">
                <a class="layui-btn layui-btn-danger layui-btn-normal delAll_btn">批量删除</a>
            </div>

            <div class="layui-inline">
                <select name="SI_Status">
                    <option value="">选择状态</option>
                    <option value="2" <?php if($param['NI_Status']==2){echo 'selected';}?>>已审核</option>
                    <option value="1" <?php if($param['NI_Status']==1){echo 'selected';}?>>待审核</option>
                    <option value="0" <?php if($param['NI_Status']===0){echo 'selected';}?>>草稿</option>
                </select>
            </div>
            <div class="layui-inline">
                <div class="layui-input-inline">
                    <input type="text" class="layui-input searchVal" placeholder="请输入搜索的内容" />
                </div>
                <a class="layui-btn search_btn" data-type="reload">搜索</a>
            </div>
        </form>
    </blockquote>
    <table id="newsList" lay-filter="newsList"></table>
    <!--审核状态-->
    <script type="text/html" id="newsStatus">
        {{#  if(d.SI_Status == "1"){ }}
        <span class="layui-orange">待审核</span>
        {{#  } else if(d.SI_Status == "2"){ }}
        <span class="layui-green">已审核</span>
        {{#  } else if(d.SI_Status == "-1"){ }}
        <span class="layui-red">已删除</span>
        {{#  } else { }}
            草稿
        {{#  }}}
    </script>

    <!--操作-->
    <script type="text/html" id="newsListBar">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
    </script>
</form>
<script type="text/javascript" src="<?php echo config('static_dir'); ?>layui/layui.js?_v=<?php echo config('static_ver'); ?>"></script>
<script type="text/javascript" src="<?php echo config('static_dir'); ?>js/my.js?_v=<?php echo config('static_ver'); ?>"></script>
<script type="text/javascript">
layui.use(['form','layer','laydate','table','laytpl'],function(){
    var form = layui.form,
        // layer = parent.layer === undefined ? layui.layer : top.layer,
        layer = layui.layer,
        $ = layui.jquery,
        laydate = layui.laydate,
        laytpl = layui.laytpl,
        table = layui.table;

    //新闻列表
    var tableIns = table.render({
        elem: '#newsList',
        url : 'list',
        cellMinWidth : 95,
        page : true,
        height : "full-125",
        limit : 20,
        limits : [10,15,20,30],
        id : "newsListTable",
        cols : [[
            {type: "checkbox", fixed:"left", width:50},
            {field: 'SI_Id', title: 'ID', width:60, align:"center"},
            {field: 'SI_AddUName', title: '发布人',  align:'center'},
            {field: 'SI_Content', title: '内容', width:'20%', templet:function(d){
                var type = '';
                // 秀吧类型 1=图片，2=视频
                if (d.SI_Type == "1") {
                    type = '<i class="layui-icon">&#xe64a;</i>&nbsp;';
                }else if(d.SI_Type == "2"){
                    type = '<i class="layui-icon">&#xe6ed;</i>&nbsp;';
                }
                return type + d.SI_Content;
            }},
            {field: 'SI_SC_Name', title: '分类', align:'center'},
            {field: 'SI_Tags', title: '标签', width:150, align:'center'},
            {field: 'SI_LocationName', title: '位置', align:'center'},
            {field: 'SI_Scope', title: '公开', width:'60', align:'center', templet:function(d){
                var type = '';
                if (d.SI_Scope == "1") {
                    type = '私密';
                }else{
                    type = '公开';
                }
                return type;
            }},
            {field: 'SI_CommentCount', title: '评论', width:'60', align:'center'},
            {field: 'SI_LikeCount', title: '点赞', width:'60', align:'center'},
            {field: 'SI_CollectionCount', title: '收藏', width:'60', align:'center'},
            {field: 'SI_ShareCount', title: '分享', width:'60', align:'center'},
            {field: 'SI_ReportCount', title: '举报', width:'60', align:'center'},
            {field: 'SI_Status', title: '状态', width:'80', align:'center',templet:"#newsStatus"},
            {field: 'SI_AddTime', title: '发布时间', align:'center', minWidth:110, templet:function(d){
                var myDate = new Date(d.SI_AddTime*1000);
                return myDate.format('MM-dd hh:mm');
            }},
            {title: '操作', width:130, templet:'#newsListBar',fixed:"right",align:"center"}
        ]]
    });

    //是否首页推荐
    form.on('switch(newsRecommend)', function(data){
        var index = layer.msg('修改中，请稍候',{icon: 16,time:false,shade:0.8});
        var recommend = data.elem.checked === true ? 1 : 0;
        $.ajax({
            url: 'act',
            type: 'post',
            dataType: 'json',
            data: {
                type: 'changeRecommend',
                id: $(this).data('id'),
                recommend: recommend,
            },
        })
        .done(function(res) {
            setTimeout(function(){
                layer.close(index);
                layer.msg(res.msg);
            },500);
        })
        .fail(function() {
            layer.msg('网络错误',{time:5000});
        });
        
    })

    //搜索【此功能需要后台配合，所以暂时没有动态效果演示】
    $(".search_btn").on("click",function(){
        table.reload("newsListTable",{
            page: {
                curr: 1 //重新从第 1 页开始
            },
            where: {
                SI_Content: $(".searchVal").val(),  //搜索的关键字
                SI_Status: $("select[name=SI_Status] option:selected").val(),
            }
        })
    });

    //添加秀吧
    function addNews(edit){
        var url = 'edit'; title = '添加秀吧';
        if (edit) {
            url = 'edit?id=' + edit.SI_Id;
            title = '编辑秀吧';
        }
        var index = layui.layer.open({
            title : title,
            type : 2,
            area : ['100%','100%'],
            content : url,
            success : function(layero, index){
                /*var body = layui.layer.getChildFrame('body', index);
                if(edit){
                    body.find(".news_title").val(edit.SI_Title);
                    body.find(".abstract").val(edit.abstract);
                    body.find(".thumbImg").attr("src",edit.newsImg);
                    body.find("#news_content").val(edit.content);
                    body.find(".newsStatus select").val(edit.newsStatus);
                    body.find(".openness input[name='openness'][title='"+edit.newsLook+"']").prop("checked","checked");
                    body.find(".newsTop input[name='newsTop']").prop("checked",edit.newsTop);
                    form.render();
                }*/
                setTimeout(function(){
                    layui.layer.tips('点击此处返回秀吧列表', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500)
            }
        })
        // layui.layer.full(index);
        //改变窗口大小时，重置弹窗的宽高，防止超出可视区域（如F12调出debug的操作）
        // $(window).on("resize",function(){
        //     layui.layer.full(index);
        // })
    }
    $(".addNews_btn").click(function(){
        addNews();
    })

    //批量删除
    $(".delAll_btn").click(function(){
        var checkStatus = table.checkStatus('newsListTable'),
            data = checkStatus.data,
            ids = [];
        if(data.length > 0) {
            for (var i in data) {
                ids.push(data[i].SI_Id);
            }
            layer.confirm('确定删除选中的秀吧？', {icon: 3, title: '提示信息'}, function (index) {
                $.post("delete",{
                    ids : ids  //将需要删除的newsId作为参数传入
                },function(res){
                    if (res.status==1) {
                        tableIns.reload();
                        layer.close(index);
                    }else{
                        layer.msg(res.msg);
                    }
                })
            })
        }else{
            layer.msg("请选择需要删除的秀吧");
        }
    })

    //列表操作
    table.on('tool(newsList)', function(obj){
        var layEvent = obj.event,
            data = obj.data;

        if(layEvent === 'edit'){ //编辑
            addNews(data);
        } else if(layEvent === 'del'){ //删除
            layer.confirm('确定删除 '+data.SI_Content+' 此秀吧？',{icon:3, title:'提示信息'},function(index){
                $.post("delete",{
                    ids : [data.SI_Id]  //将需要删除的newsId作为参数传入
                },function(res){
                    if (res.status==1) {
                        tableIns.reload();
                        layer.close(index);
                    }else{
                        layer.msg(res.msg);
                    }
                })
            });
        } else if(layEvent === 'look'){ //预览
            layer.alert("此功能需要前台展示，实际开发中传入对应的必要参数进行秀吧内容页面访问")
        }
    });

})
</script>
</body>
</html>