项目API文档说明
===============
接口分5个模块组成，分别为：

 + 资讯
 + 课外
 + 助手(天天向上)
 + 服务
 + 个人

> 文档中的 domain 为实际环境中的域名，目前是 api.aixuejie.ajeelee.com；
> 请以POST方式发送数据

## 秀吧接口
* 秀吧列表
```
url: http://<domain>/showme/list
```
请求数据
```
1，page 页码
2，page_size 每页数量
3，SI_Type 信息类型1=图片，2=视频
4，SI_SC_Id 分类ID
5，SI_Scope 数据范围 0公开，1不公开
6，SI_Tags 中文标签名，多个逗号分隔
7，SI_AddUId 发布人ID
事例：
{
    "page":1,
    "page_size":1
}
```
返回数据
```
{
    "Code": 200,
    "Msg": "查询成功",
    "Data": {
        "count": 20,
        "page_size": 1,
        "page": 1,
        "pages": 20,
        "list": [
            {
                "SI_Id": 20,
                "SI_Type": 1,
                "SI_SC_Id": "22",
                "SI_SC_Name": "22",
                "SI_Tags": "美颜",
                "SI_Content": "秀吧的内容",
                "SI_Materials": "",
                "SI_MaterialsFace": null,
                "SI_MaterialsCount": null,
                "SI_LocationName": null,
                "SI_LocationLat": null,
                "SI_LocationLong": null,
                "SI_Scope": 1,
                "SI_CommentCount": 0,
                "SI_LikeCount": 0,
                "SI_CollectionCount": 0,
                "SI_ReportCount": 0,
                "SI_ShareCount": 0,
                "SI_Status": 2,
                "SI_AddUId": 0,
                "SI_AddUName": "",
                "SI_AddAvatar": "",
                "SI_AddTime": 0,
                "SI_AddIP": "",
                "SI_AddDate": "1970.01.01",
                "screenWidth": null,
                "picNum": 0,
                "attachments": []
            }
        ]
    }
}
```

* 秀吧详情
```
url: http://<domain>/showme/detail
```
请求数据
```
SI_Id 秀吧ID
事例：
{
    "SI_Id":20
}
```
返回数据
```
{
    "Code": 200,
    "Msg": "查询成功",
    "Data": {
        ...
    }
}
```

* 秀吧添加
```
url: http://<domain>/showme/add
```

* 秀吧修改
```
url: http://<domain>/showme/update
```

* 秀吧删除
```
url: http://<domain>/showme/delete
```

## 看板接口
* 看板列表
```
url: http://<domain>/board/list
```
#### 请求数据
```
1，page 页码
2，page_size 每页数量
3，NB_Type 看板类型：1=便签,2=海报
4，NB_ProvCode 省份ID
5，NB_Title 标题关键字
6，NB_Tags 中文标签名，多个逗号分隔
事例：
{
	"page":1,
	"page_size":1,
	"NB_Type":"",
	"NB_ProvCode":0,
	"NB_Title":"",
	"NB_Tags":"电影,台球,唱歌"
}
```
#### 返回数据
```
{
    "Code": 200,
    "Msg": "查询成功",
    "Ddata": {
        "count": 2,
        "page_size": 1,
        "page": 1,
        "pages": 2,
        "list": [
            {
                "NB_Id": 2,
                "NB_Title": "海报测试",
                "NB_Type": 2,
                "NB_NC_Id": 0,
                "NB_Tags": "新标签36,阅读",
                "NB_ProvCode": 13,
                "NB_ProvName": "河北省",
                "NB_CityCode": null,
                "NB_CityName": null,
                "NB_Summary": null,
                "NB_Picture": "",
                "NB_SchoolIds": "11119,11118",
                "NB_SchoolNames": "河北经贸大学经济管理学院,河北大学工商学院",
                "NB_AddUId": 0,
                "NB_AddUName": "",
                "NB_AddTime": 1536941001,
                "NB_AddIP": "60.186.31.96",
                "NB_UpdateUId": null,
                "NB_UpdateUName": null,
                "NB_UpdateTime": 1537892322,
                "NB_UpdateIP": "218.72.51.178",
                "NB_CommentIsOpen": -1,
                "NB_CommentCount": 0,
                "NB_LikeCount": 0,
                "NB_CollectionCount": 0,
                "NB_ReportCount": 0,
                "NB_ShareCount": 0,
                "NB_IsRecommendIndex": 0,
                "NB_ExpiryTime": null,
                "NB_Status": 2,
                "picNum": 1,
                "attachments": [
                    {
                        "A_Id": 22,
                        "A_IsBackground": 1,
                        "A_AttachName": "saddest_pug_dog-wallpaper-3840x2400.jpg",
                        "A_AttachType": "image/jpeg",
                        "A_AttachPath": "http://pd4c8babk.bkt.clouddn.com/images/board/20180915/5b9be1baab19f982.jpg",
                        "A_AttachSize": 538.034,
                        "A_ImageWidth": 3840,
                        "A_ImageHeight": 2400
                    }
                ]
            }
        ]
    }
}
```

* 看板详情
```
url: http://<domain>/board/detail
```
请求数据
```
NB_Id 看板ID
事例：
{
	"NB_Id":1
}
```
返回数据
```
{
    "Code": 200,
    "Msg": "查询成功",
    "Data": {
        ...
    }
}
```

* 看板添加
```
url: http://<domain>/board/add
```

* 看板修改
```
url: http://<domain>/board/update
```

* 看板删除
```
url: http://<domain>/board/delete
```

## 资讯接口
* 资讯列表
```
url: http://<domain>/news/list
```
请求数据
```
1，page 页码
2，page_size 每页数量
3，NI_Module 模块ID，101=新闻资讯,201=秀吧...参考枚举说明
4，NI_Type 资讯类型：1=图文,2=视频
5，NI_ProvCode 省份ID
6，NI_Title 标题关键字
7，NI_Tags 中文标签名，多个逗号分隔
事例：
{
	"page":1,
	"page_size":5,
	"NI_Module":101,
	"NI_Type":"1",
	"NI_ProvCode":0,
	"NI_Title":"",
	"NI_Tags":"电影,台球,唱歌"
}
```
返回数据
```
{
    "Code": 200,
    "Msg": "查询成功",
    "Data": {
        "count": 2,
        "page_size": 1,
        "page": 1,
        "pages": 2,
        "list": [
            {
                "NI_Id": 20,
                "NI_Module": 101,
                "NI_Type": 1,
                "NI_Title": "综合测试资讯2",
                "NI_SubTitle": "哈哈哈，副标题",
                "NI_Tags": "阅读,唱歌",
                "NI_ProvCode": 81,
                "NI_ProvName": "香港特别行政区",
                "NI_CityCode": null,
                "NI_CityName": null,
                "NI_Summary": "",
                "NI_Picture": "",
                "NI_IsBigPicture": 0,
                "NI_VideoUrl": "",
                "NI_SchoolIds": "30001,12001,8005,8001,1105,1102",
                "NI_SchoolNames": "浙江大学,郑州大学,山东师大,山东大学,朝阳二外,北大资源学院",
                "NI_SourceUrl": null,
                "NI_SourceName": null,
                "NI_AddUId": 0,
                "NI_AddUName": null,
                "NI_AddTime": 1536370648,
                "NI_AddIP": "115.197.32.35",
                "NI_UpdateUId": null,
                "NI_UpdateUName": null,
                "NI_UpdateTime": 1537890785,
                "NI_UpdateIP": "218.72.51.178",
                "NI_CommentIsOpen": -1,
                "NI_CommentCount": 0,
                "NI_LikeCount": 0,
                "NI_CollectionCount": 0,
                "NI_ReportCount": 0,
                "NI_ShareCount": 0,
                "NI_IsRecommendIndex": 0,
                "NI_ExpiryTime": null,
                "NI_Status": 2,
                "picNum": 3,
                "attachments": [
                    {
                        "A_Id": 30,
                        "A_IsBackground": 1,
                        "A_AttachName": "life_34-wallpaper-3840x2400.jpg",
                        "A_AttachType": "image/jpeg",
                        "A_AttachPath": "http://pd4c8babk.bkt.clouddn.com/images/news/20180925/5baa59d39d03b657.jpg",
                        "A_AttachSize": 638.507,
                        "A_ImageWidth": 3840,
                        "A_ImageHeight": 2400
                    },
                    {
                        "A_Id": 29,
                        "A_IsBackground": 1,
                        "A_AttachName": "empty_road_3-wallpaper-1920x1200.jpg",
                        "A_AttachType": "image/jpeg",
                        "A_AttachPath": "http://pd4c8babk.bkt.clouddn.com/images/news/20180925/5baa59d3d63af531.jpg",
                        "A_AttachSize": 426.528,
                        "A_ImageWidth": 1920,
                        "A_ImageHeight": 1200
                    },
                    {
                        "A_Id": 28,
                        "A_IsBackground": 1,
                        "A_AttachName": "particle_4k_3-wallpaper-3554x1999.jpg",
                        "A_AttachType": "image/jpeg",
                        "A_AttachPath": "http://pd4c8babk.bkt.clouddn.com/images/news/20180925/5baa59d38e1d7638.jpg",
                        "A_AttachSize": 560.871,
                        "A_ImageWidth": 3554,
                        "A_ImageHeight": 1999
                    }
                ]
            }
        ]
    }
}
```

* 资讯详情
```
url: http://<domain>/news/detail
```
请求数据
```
NI_Id 资讯ID
事例：
{
	"NI_Id":20
}
```
返回数据
```
{
    "Code": 200,
    "Msg": "查询成功",
    "Data": {
        ...
    }
}
```

* 资讯添加
```
url: http://<domain>/news/add
```

* 资讯修改
```
url: http://<domain>/news/update
```

* 资讯删除
```
url: http://<domain>/news/delete
```

## 上传通用接口
* 上传文件
```
url: http://<domain>/storage/upload
```
#### 请求数据
```
1，file 表单名为file的文件资源
2，type 上传的文件类型，可选值[file,images,video]
3，module 模块名/分类名
4，module_type 模块类型：101=新闻资讯,201=秀吧...参考枚举说明
5，backend 是否是后台，共享数据用的,1是后台，非1是用户
6，user_id 上传人Id
7，user_name 上传人名称
事例：
{
	"file": stream,
	"type": "images",
	"module": "news",
	"module_type": 101,
	"backend": 1,
	"user_id": 1,
	"user_name": "老张"
}
```
#### 返回数据
```
{
    "Code": 200,
    "Msg": "上传成功",
    "Data": {
        "name": "5badf3f305125471.mp4",
        "url": "http://pd4c8babk.bkt.clouddn.com/video/board/20180928/5bae47c556fd2688.mp4",
        "id": "40",
        "screenshot": "http://pd4c8babk.bkt.clouddn.com/video/board/20180928/5bae47c556fd2688_480x360.jpg"
    }
}
```