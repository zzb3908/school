项目介绍
===============
+ 630为一套基于互联网的，以校园人群和泛校园人群为用户的综合软件系统。其产品形态，软件上表现为移动端软件(用户端)，固定端软件(管理端)以及服务器软件(服务器端)；硬件上表现为服务器，存储设备等。

+ 630定位为一个综合网络生态系统。

+ 首先，它具备平台功能，为众多服务提供运行空间或者接口，这些服务有些是平台直接营运的，有些由第三方提供；
    其次，它是一个公益和商业并存的综合服务集中地。


组成
===============

移动端软件由5个模块组成，分别为：

 + 资讯
 + 课外
 + 助手(天天向上)
 + 服务
 + 个人

> 每个模块又由4-6个子模块组成。

## 运行环境
* 基于docker环境快速部署，快速迭代。

* 创建容器命令
```
Redis容器：docker run -d --restart=always --name redis redis redis-server
Redis测试：docker run -it --link redis:redis --rm redis redis-cli -h redis -p 6379
Web容器：docker run -d --restart=always --link redis:redis-host --name web -v /data/wwwroot/xiangyata/web/:/wjdata/ -p 2201:22 -p 8001:80 centos6.9-php56-nginx:2.0 /root/run.sh
原型站点：docker run -d --restart=always --name pro -v /data/wwwroot/xiangyata/pro/:/wjdata/ -p 2203:22 -p 8003:80 centos6.9-php56-nginx:2.0 /root/run.sh
```
* 容器操作
```
启动容器：docker start web
停止容器：docker stop web
重启容器：docker restart web
删除容器：docker rm web
```

## 开发进度
+ 新增学校管理、标签管理 2018-08-28
+ 新增原型Pro站点 2018-08-20
+ 完善资讯管理功能 2018-08-17
+ 资讯接口完成 2018-08-14
+ 第一次上传代码 2018-08-11