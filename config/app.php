<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

// error_reporting(E_ALL | E_STRICT); //开发环境 输出所有错误
error_reporting(E_ERROR | E_PARSE); //正式环境 输出致命错误,语法错误

return [
    'api_host'  => 'http://api.'.DOMAIN.'/',
    'upload_host' => 'http://pd4c8babk.bkt.clouddn.com/',

    /*
    `T_Module` smallint(6) NOT NULL COMMENT '模块：101综合，102校际，103校内，104研学，105看板，201秀吧等等',
    模块分类ID
    101=资讯：综合
    102=资讯：校际
    103=资讯：校内
    104=资讯：研学
    105=资讯：看板

    201=课外：秀吧
    202=课外：约吧
    203=课外：聚吧
    204=课外：赛吧
    205=课外：读吧

    301=助手：日程
    302=助手：班级
    303=助手：社团
    30301=助手：社团->活动
    304=助手：课程
    305=助手：课题组

    401=服务：商场
    402=服务：金融服务
    403=服务：工作
    404=服务：安全与健康
    */
    'module_type'   => [
        // 资讯
        ['id' => 101, 'name' => '综合', 'pid'=>100],
        ['id' => 102, 'name' => '校际', 'pid'=>100],
        ['id' => 103, 'name' => '校内', 'pid'=>100],
        // ['id' => 104, 'name' => '研学', 'pid'=>100],// 不要这个类了 2018-09-26
        ['id' => 105, 'name' => '看板', 'pid'=>100],

        // 课外
        ['id' => 201, 'name' => '秀吧', 'pid'=>200],
        ['id' => 202, 'name' => '约吧', 'pid'=>200],
        ['id' => 203, 'name' => '聚吧', 'pid'=>200],
        ['id' => 204, 'name' => '赛吧', 'pid'=>200],
        ['id' => 205, 'name' => '读吧', 'pid'=>200],

        // 助手
        ['id' => 301, 'name' => '日程', 'pid'=>300],
        ['id' => 302, 'name' => '班级', 'pid'=>300],
        ['id' => 303, 'name' => '社团', 'pid'=>300],
        ['id' => 30301, 'name' => '活动', 'pid'=>303],
        ['id' => 304, 'name' => '课程', 'pid'=>300],
        ['id' => 305, 'name' => '课题组', 'pid'=>300],

        // 服务
        ['id' => 401, 'name' => '商场', 'pid'=>400],
        ['id' => 402, 'name' => '金融服务', 'pid'=>400],
        ['id' => 403, 'name' => '工作', 'pid'=>400],
        ['id' => 404, 'name' => '安全与健康', 'pid'=>400],
    ],

    // 应用名称
    'app_name'               => '',
    // 应用地址
    'app_host'               => '',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'Asia/Shanghai',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => 'Model',
    // 控制器类后缀
    'controller_suffix'      => 'Controller',

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空模块名
    'empty_module'           => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法前缀
    'use_action_prefix'      => false,
    // 操作方法后缀
    'action_suffix'          => 'Action',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // HTTPS代理标识
    'https_agent_name'       => '',
    // IP代理获取标识
    'http_agent_ip'          => 'X-REAL-IP',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由延迟解析
    'url_lazy_route'         => false,
    // 是否强制使用路由
    'url_route_must'         => false,
    // 合并路由规则
    'route_rule_merge'       => false,
    // 路由是否完全匹配
    'route_complete_match'   => false,
    // 使用注解路由
    'route_annotation'       => false,

    // 域名根，如thinkphp.cn
    'url_domain_root'        => DOMAIN,
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],
    // 是否开启路由缓存
    'route_check_cache'      => false,
    // 路由缓存的Key自定义设置（闭包），默认为当前URL和请求类型的md5
    'route_check_cache_key'  => '',
    // 路由缓存类型及参数
    'route_cache_option'     => [],

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => Env::get('think_path') . 'tpl/dispatch_jump.tpl',
    'dispatch_error_tmpl'    => Env::get('think_path') . 'tpl/dispatch_jump.tpl',

    // 异常页面的模板文件
    'exception_tmpl'         => Env::get('think_path') . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => \app\common\lib\exception\ApiHandleException::class,


    'redis'                  => [
        'host'       => 'redis-host',
        'port'       => 6379,
        'password'   => '',
        'select'     => 0,//选择库范围 0~15，对应db0~db15，默认值0
        'timeout'    => 0,
        'expire'     => 0,
        'persistent' => false,
        'prefix'     => 'XYT_',
        'serialize'  => true,
    ],

];
