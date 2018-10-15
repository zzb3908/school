<?php
namespace app\manage\controller;

class IndexController extends BaseController
{
    public function indexAction(){
        return $this->fetch();
    }

    public function mainAction(){
        return $this->fetch();
    }

    public function defaultAction(){
        return $this->fetch();
    }

    /**
     * 获取菜单
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-12
     * @author      iclubs <iclubs@126.com>
     */
    public function menuAction(){
        //菜单布局
    	$menu = [
            'contentManagement' => [
                [
                    'title' => '资讯列表',
                    'icon' => 'icon-text',
                    'href' => '/news/index',
                    'spread' => false,
                ],
                [
                    'title' => '评论列表',
                    'icon' => 'icon-text',
                    'href' => '/news/comments',
                    'spread' => false,
                ],
                [
                    'title' => '看板列表',
                    'icon' => 'icon-text',
                    'href' => '/board/index',
                    'spread' => false,
                ],
                /*[
                    'title' => '图片管理',
                    'icon' => '&#xe634;',
                    'href' => '/images/index',
                    'spread' => false,
                ],
                [
                    'title' => '其他页面',
                    'icon' => '&#xe630;',
                    'href' => '',
                    'spread' => false,
                    'children' => [
                        [
                        'title' => '404页面',
                        'icon' => '&#xe61c;',
                        'href' => '/static/manage/page/404.html',
                        'spread' => false,
                        ],
                        [
                        'title' => '登录',
                        'icon' => '&#xe609;',
                        'href' => '/static/manage/page/login/login.html',
                        'spread' => false,
                        'target' => '_blank',
                        ],
                    ],
                ],*/
            ],
            'extracurricularManagement' => [
                [
                    'title' => '秀吧管理',
                    'icon' => 'icon-text',
                    'href' => '',
                    'spread' => false,
                    'children' => [
                        [
                            'title' => '秀吧列表',
                            'icon' => 'icon-text',
                            'href' => '/showme/index',
                            'spread' => false,
                        ],
                        [
                            'title' => '秀吧分类管理',
                            'icon' => 'icon-text',
                            'href' => '/showme/category',
                            'spread' => false,
                        ],
                    ],
                ],
                [
                    'title' => '约吧管理',
                    'icon' => 'icon-text',
                    'href' => '',
                    'spread' => false,
                    'children' => [
                        [
                            'title' => '约吧列表',
                            'icon' => 'icon-text',
                            'href' => '/invite/index',
                            'spread' => false,
                        ],
                        [
                            'title' => '约吧留言',
                            'icon' => 'icon-text',
                            'href' => '/invite/messages',
                            'spread' => false,
                        ],
                    ],
                ],
                [
                    'title' => '聚吧管理',
                    'icon' => 'icon-text',
                    'href' => '',
                    'spread' => false,
                    'children' => [
                        [
                            'title' => '聚吧列表',
                            'icon' => 'icon-text',
                            'href' => '/topic/index',
                            'spread' => false,
                        ],
                        [
                            'title' => '小组管理',
                            'icon' => 'icon-text',
                            'href' => '/topic/group',
                            'spread' => false,
                        ],
                    ],
                ],
                [
                    'title' => '赛吧管理',
                    'icon' => 'icon-text',
                    'href' => '',
                    'spread' => false,
                    'children' => [
                        [
                            'title' => '赛吧列表',
                            'icon' => 'icon-text',
                            'href' => '/competition/index',
                            'spread' => false,
                        ],
                        [
                            'title' => '比赛类别',
                            'icon' => 'icon-text',
                            'href' => '/competition/category/1',
                            'spread' => false,
                        ],
                        [
                            'title' => '赛事级别',
                            'icon' => 'icon-text',
                            'href' => '/competition/category/2',
                            'spread' => false,
                        ],
                    ],
                ],
                [
                    'title' => '读吧管理',
                    'icon' => 'icon-text',
                    'href' => '',
                    'spread' => false,
                    'children' => [
                        [
                            'title' => '书籍管理',
                            'icon' => 'icon-text',
                            'href' => '/book/index',
                            'spread' => false,
                        ],
                        [
                            'title' => '书评管理',
                            'icon' => 'icon-text',
                            'href' => '/book/messages',
                            'spread' => false,
                        ],
                        [
                            'title' => '专题文章',
                            'icon' => 'icon-text',
                            'href' => '/book/specialcolumn',
                            'spread' => false,
                        ],
                        [
                            'title' => '书籍类别管理',
                            'icon' => 'icon-text',
                            'href' => '/book/category',
                            'spread' => false,
                        ],
                    ],
                ],
            ],
            'helperManagement' => [
                [
                    'title' => '日程管理',
                    'icon' => 'icon-text',
                    'href' => '/Calendar/index',
                    'spread' => false,
                ],
                [
                    'title' => '课程管理',
                    'icon' => 'icon-text',
                    'href' => '/index/default',
                    'spread' => false,
                    'children' => [
                        [
                            'title' => '作业管理',
                            'icon' => 'icon-text',
                            'href' => '/index/default',
                            'spread' => false,
                        ],
                        [
                            'title' => '答疑管理',
                            'icon' => 'icon-text',
                            'href' => '/index/default',
                            'spread' => false,
                        ],
                    ],
                ],
                [
                    'title' => '班级管理',
                    'icon' => 'icon-text',
                    'href' => '/index/default',
                    'spread' => false,
                    'children' => [
                        [
                            'title' => '班级列表',
                            'icon' => 'icon-text',
                            'href' => '/class/index',
                            'spread' => false,
                        ],
                        [
                            'title' => '学生管理',
                            'icon' => 'icon-text',
                            'href' => '/class/userindex',
                            'spread' => false,
                        ],
                        [
                            'title' => '班级通知',
                            'icon' => 'icon-text',
                            'href' => '/Notifications/index?module=302',
                            'spread' => false,
                        ],
                        [
                            'title' => '请假管理',
                            'icon' => 'icon-text',
                            'href' => '/class/classleave',
                            'spread' => false,
                        ],
                    ],
                ],
                [
                    'title' => '社团管理',
                    'icon' => 'icon-text',
                    'href' => '/index/default',
                    'spread' => false,
                    'children' => [
                        [
                            'title' => '社团活动',
                            'icon' => 'icon-text',
                            'href' => '/index/default',
                            'spread' => false,
                            'children' => [
                                [
                                    'title' => '活动成员',
                                    'icon' => 'icon-text',
                                    'href' => '/index/default',
                                    'spread' => false,
                                ],
                                [
                                    'title' => '照片管理',
                                    'icon' => 'icon-text',
                                    'href' => '/index/default',
                                    'spread' => false,
                                ],
                            ],
                        ],
                        [
                            'title' => '相册管理',
                            'icon' => 'icon-text',
                            'href' => '/index/default',
                            'spread' => false,
                        ],
                        [
                            'title' => '社团成员',
                            'icon' => 'icon-text',
                            'href' => '/index/default',
                            'spread' => false,
                        ],
                    ],
                ],
                [
                    'title' => '课题组管理',
                    'icon' => 'icon-text',
                    'href' => '/index/default',
                    'spread' => false,
                ],
            ],
            'serviceManagement' => [
                [
                    'title' => '商城管理',
                    'icon' => 'icon-text',
                    'href' => '/index/default',
                    'spread' => false,
                    'children' => [
                        [
                            'title' => '商品管理',
                            'icon' => 'icon-text',
                            'href' => '/index/default',
                            'spread' => false,
                        ],
                        [
                            'title' => '租赁',
                            'icon' => 'icon-text',
                            'href' => '/index/default',
                            'spread' => false,
                        ],
                        [
                            'title' => '购物车',
                            'icon' => 'icon-text',
                            'href' => '/index/default',
                            'spread' => false,
                        ],
                        [
                            'title' => '订单管理',
                            'icon' => 'icon-text',
                            'href' => '/index/default',
                            'spread' => false,
                        ],
                    ],
                ],
                [
                    'title' => '金融管理',
                    'icon' => 'icon-text',
                    'href' => '/index/default',
                    'spread' => false,
                ],
                [
                    'title' => '工作管理',
                    'icon' => 'icon-text',
                    'href' => '/index/default',
                    'spread' => false,
                ],
                [
                    'title' => '留学管理',
                    'icon' => 'icon-text',
                    'href' => '/index/default',
                    'spread' => false,
                ],
                [
                    'title' => '健康管理',
                    'icon' => 'icon-text',
                    'href' => '/index/default',
                    'spread' => false,
                ],
            ],
            'memberCenter' => [
                [
                    'title' => '用户中心',
                    'icon' => 'icon-text',
                    'href' => '/users/index',
                    'spread' => false,
                ],
                // [
                //     'title' => '会员等级',
                //     'icon' => 'icon-vip',
                //     'href' => '/static/manage/page/user/userGrade.html',
                //     'spread' => false,
                // ],
            ],
            'systemeSttings' => [
                [
                    'title' => '学校管理',
                    'icon' => 'icon-text',
                    'href' => '/schools/index',
                    'spread' => false,
                ],
                [
                    'title' => '标签管理',
                    'icon' => 'icon-text',
                    'href' => '/tags/index',
                    'spread' => false,
                ],
                // [
                //     'title' => '系统基本参数',
                //     'icon' => '&#xe631;',
                //     'href' => '/static/manage/page/systemSetting/basicParameter.html',
                //     'spread' => false,
                // ],
                // [
                //     'title' => '系统日志',
                //     'icon' => 'icon-log',
                //     'href' => '/static/manage/page/systemSetting/logs.html',
                //     'spread' => false,
                // ],
                // [
                //     'title' => '友情链接',
                //     'icon' => '&#xe64c;',
                //     'href' => '/static/manage/page/systemSetting/linkList.html',
                //     'spread' => false,
                // ],
                // [
                //     'title' => '图标管理',
                //     'icon' => '&#xe857;',
                //     'href' => '/static/manage/page/systemSetting/icons.html',
                //     'spread' => false,
                // ],
            ],
            'seraphApi' => [
                [
                    'title' => '三级联动模块',
                    'icon' => 'icon-mokuai',
                    'href' => '/static/manage/page/doc/addressDoc.html',
                    'spread' => false,
                ],
                [
                    'title' => 'bodyTab模块',
                    'icon' => 'icon-mokuai',
                    'href' => '/static/manage/page/doc/bodyTabDoc.html',
                    'spread' => false,
                ],
                [
                    'title' => '三级菜单',
                    'icon' => 'icon-mokuai',
                    'href' => '/static/manage/page/doc/navDoc.html',
                    'spread' => false,
                ],
            ],
        ];

        return json($menu);
    }

}
