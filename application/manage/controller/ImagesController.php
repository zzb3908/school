<?php
/**
 * 图片库管理
 * ImagesController.php
 * @version     v1.0
 * @date        2018-08-12
 * @author      iclubs <iclubs@126.com>
 * @copyright   Copyright (c) Openver.com 
 * @link        http://www.openver.com
 */

namespace app\manage\controller;

class ImagesController extends BaseController{

    public function indexAction(){
        return $this->fetch();
    }

    /**
     * 图片列表
     * @access      public
     * @param       参数
     * @return      json
     * @date        2018-08-12
     * @author      iclubs <iclubs@126.com>
     */
    public function listAction(){
        //菜单布局
        $images = [
            'title' => '图片管理',
            'id' => 'Images',
            'start' =>0,
            'data' => [
                [
                    'src' => config('static_dir')."images/userface1.jpg",
                    'thumb' => config('static_dir')."images/userface1.jpg",
                    'alt' => '美女生活照1',
                    'pid' => 1,
                ],
                [
                    'src' => config('static_dir')."images/userface2.jpg",
                    'thumb' => config('static_dir')."images/userface2.jpg",
                    'alt' => '美女生活照2',
                    'pid' => 2,
                ],
                [
                    'src' => config('static_dir')."images/userface3.jpg",
                    'thumb' => config('static_dir')."images/userface3.jpg",
                    'alt' => '美女生活照3',
                    'pid' => 3,
                ],
                [
                    'src' => config('static_dir')."images/userface4.jpg",
                    'thumb' => config('static_dir')."images/userface4.jpg",
                    'alt' => '美女生活照4',
                    'pid' => 4,
                ],
                [
                    'src' => config('static_dir')."images/userface5.jpg",
                    'thumb' => config('static_dir')."images/userface5.jpg",
                    'alt' => '美女生活照5',
                    'pid' => 5,
                ],
                [
                    'src' => config('static_dir')."images/userface1.jpg",
                    'thumb' => config('static_dir')."images/userface1.jpg",
                    'alt' => '美女生活照1',
                    'pid' => 6,
                ],
                [
                    'src' => config('static_dir')."images/userface2.jpg",
                    'thumb' => config('static_dir')."images/userface2.jpg",
                    'alt' => '美女生活照2',
                    'pid' => 7,
                ],
                [
                    'src' => config('static_dir')."images/userface3.jpg",
                    'thumb' => config('static_dir')."images/userface3.jpg",
                    'alt' => '美女生活照3',
                    'pid' => 8,
                ],
                [
                    'src' => config('static_dir')."images/userface4.jpg",
                    'thumb' => config('static_dir')."images/userface4.jpg",
                    'alt' => '美女生活照4',
                    'pid' => 9,
                ],
                [
                    'src' => config('static_dir')."images/userface5.jpg",
                    'thumb' => config('static_dir')."images/userface5.jpg",
                    'alt' => '美女生活照5',
                    'pid' => 10,
                ],
                [
                    'src' => config('static_dir')."images/userface1.jpg",
                    'thumb' => config('static_dir')."images/userface1.jpg",
                    'alt' => '美女生活照1',
                    'pid' => 11,
                ],
                [
                    'src' => config('static_dir')."images/userface2.jpg",
                    'thumb' => config('static_dir')."images/userface2.jpg",
                    'alt' => '美女生活照2',
                    'pid' => 12,
                ],
                [
                    'src' => config('static_dir')."images/userface3.jpg",
                    'thumb' => config('static_dir')."images/userface3.jpg",
                    'alt' => '美女生活照3',
                    'pid' => 13,
                ],
                [
                    'src' => config('static_dir')."images/userface4.jpg",
                    'thumb' => config('static_dir')."images/userface4.jpg",
                    'alt' => '美女生活照4',
                    'pid' => 14,
                ],
                [
                    'src' => config('static_dir')."images/userface5.jpg",
                    'thumb' => config('static_dir')."images/userface5.jpg",
                    'alt' => '美女生活照5',
                    'pid' => 15,
                ],
                [
                    'src' => config('static_dir')."images/userface1.jpg",
                    'thumb' => config('static_dir')."images/userface1.jpg",
                    'alt' => '美女生活照1',
                    'pid' => 16,
                ],
                [
                    'src' => config('static_dir')."images/userface2.jpg",
                    'thumb' => config('static_dir')."images/userface2.jpg",
                    'alt' => '美女生活照2',
                    'pid' => 17,
                ],
                [
                    'src' => config('static_dir')."images/userface3.jpg",
                    'thumb' => config('static_dir')."images/userface3.jpg",
                    'alt' => '美女生活照3',
                    'pid' => 18,
                ],
                [
                    'src' => config('static_dir')."images/userface4.jpg",
                    'thumb' => config('static_dir')."images/userface4.jpg",
                    'alt' => '美女生活照4',
                    'pid' => 19,
                ],
                [
                    'src' => config('static_dir')."images/userface5.jpg",
                    'thumb' => config('static_dir')."images/userface5.jpg",
                    'alt' => '美女生活照5',
                    'pid' => 20,
                ],
            ]
        ];

        return json($images);
    }
}
