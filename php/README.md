# 项目介绍


* * * * *


OneBase是一个基于ThinkPHP5的免费开源，快速简单，面向对象的应用研发架构，是为了快速研发应用而诞生。在保持出色的性能和新颖设计思想同时，也注重易用性。遵循Apache2开源许可协议发布，您可以免费使用OneBase将研发的产品发布或销售，但不能未经授权抹除产品标志再次用于开源。


 **规范** ： OneBase 提供一套编码规范，可使团队研发协作事半功倍。

 **严谨** ： 异常严谨的错误检测和安全机制，详细的日志信息，为您的研发保驾护航。

 **灵活** ： 分层，服务，插件等合理的解耦合设计使您升级框架或需求变更得心应手。

 **接口** ： 完善的接口架构，只需关注业务逻辑研发，省心省力。

 **高效** ： 清晰的系统执行流程，不浪费一丝效率。

 **特色** ： 权限继承，资源回收，前缀对象定位，各种设计思想。

 **精简** ： 精简到极致的控制器，强迫症患者的良药。

 **体验** ： 后台PJAX模式可自由切换，速度快到无法呼吸。

 **进阶** ： 索引，分表分库，Redis，分布式，集群，负载均衡。





# 快速体验入口


* * * * *



官网首页：http://obstore.cn

后台演示：http://demo.onebase.org/admin.php （账号:demo，密码:111111，后台演示仅部分权限，完整权限需部署安装）

接口演示：http://demo.onebase.org/api.php

前端演示：http://demo.onebase.org

视频演示：http://static.onebase.org/OneBase.mp4

源码下载：https://gitee.com/Bigotry/OneBase

研发文档：http://document.onebase.org


# 系统架构图


* * * * *

![OneBase系统架构图](https://gitee.com/uploads/images/2017/1228/112704_2e32357d_917834.png "OneBase系统架构图.png")



# 接口模块双向数据验证流程图


* * * * *

![接口模块双向数据验证流程图](https://gitee.com/uploads/images/2018/0201/115449_8acedb9d_917834.png "ApiSafety.png")


# 部分效果图


* * * * *



![后台登录页](https://gitee.com/uploads/images/2017/1209/191515_8b70de83_917834.png "admin1.png")

![执行速度分析页](https://gitee.com/uploads/images/2017/1209/191540_b993d7da_917834.png "admin2.png")

![权限结构等级页](https://gitee.com/uploads/images/2017/1209/191608_efbcf5ed_917834.png "admin3.png")

![执行记录接口范围页](https://gitee.com/uploads/images/2017/1209/191635_5b1b2c40_917834.png "admin4.png")

![API首页](https://gitee.com/uploads/images/2017/1209/191730_5e8ed89f_917834.png "api1.png")

![API详情页](https://gitee.com/uploads/images/2017/1209/191745_5e2fb2a1_917834.png "api2.png")


# 组织结构


* * * * *



```
project                             应用部署目录
├─addon                             插件目录
│  ├─editor                         插件
│  │  ├─controller                  插件控制器目录
│  │  ├─data                        插件数据如安装卸载脚本目录
│  │  ├─logic                       插件逻辑目录
│  │  ├─static                      插件静态资源目录
│  │  ├─view                        插件视图目录
│  │  └─Editor.php                  插件类文件
│  ├─ ...                           更多插件
│  └─AddonInterface.php             插件接口文件
├─app                               应用目录
│  ├─common                         公共模块目录
│  │  ├─behavior                    系统行为目录
│  │  │  ├─AppBegin.php             应用开始行为
│  │  │  ├─AppEnd.php               应用结束行为
│  │  │  ├─InitBase.php             应用初始化基础信息行为
│  │  │  └─InitHook.php             应用初始化钩子与插件行为
│  │  ├─controller                  系统公用控制器目录
│  │  │  ├─AddonBase.php            插件控制器基类
│  │  │  └─ControllerBase.php       系统通用控制器基类
│  │  ├─logic                       系统公用逻辑目录
│  │  ├─model                       系统公用模型目录
│  │  ├─validate                    系统公用验证目录
│  │  ├─service                     系统公用服务目录
│  │  │  ├─pay                      支付服务目录
│  │  │  ├─storage                  云存储服务目录
│  │  │  ├─BaseInterface.php        服务接口
│  │  │  ├─ServiceBase.php          服务基础类
│  │  │  ├─Pay.php                  支付服务类
│  │  │  ├─Storage.php              云存储服务类
│  │  │  └─ ...                     更多服务
│  │  └─view                        系统公用视图目录
│  ├─api                            API模块目录
│  │  ├─controller                  API接口控制器目录
│  │  ├─error                       API错误码目录
│  │  ├─logic                       API业务逻辑目录
│  │  └─...                         更多目录
│  ├─admin                          后台模块目录
│  ├─index                          前端模块目录
│  ├─install                        安装模块目录
│  ├─command.php                    命令行工具配置文件
│  ├─common.php                     应用公共（函数）文件
│  ├─config.php                     应用（公共）配置文件
│  ├─database.php                   数据库配置文件
│  ├─tags.php                       应用行为扩展定义文件
│  ├─route.php                      路由配置文件
│  └─...                            更多模块与文件
├─data                              数据库备份目录
├─extend                            扩展类库目录
├─tool                              工具目录
├─public                            Web 部署目录（对外访问目录）
│  ├─static                         静态资源存放目录(css,js,image)
│  ├─upload                         系统文件上传存放目录
│  ├─index.php                      应用前端入口文件
│  ├─api.php                        应用API接口入口文件
│  ├─admin.php                      应用后台入口文件
│  └─.htaccess                      用于 apache 的重写
├─runtime                           应用的运行时目录（可写，可设置）
├─vendor                            第三方类库目录（Composer）
├─thinkphp                          框架系统目录
│  ├─lang                           语言包目录
│  ├─library                        框架核心类库目录
│  │  ├─think                       Think 类库包目录
│  │  └─traits                      系统 Traits 目录
│  ├─tpl                            系统模板目录
│  ├─.travis.yml                    CI 定义文件
│  ├─base.php                       基础定义文件
│  ├─composer.json                  composer 定义文件
│  ├─console.php                    控制台入口文件
│  ├─convention.php                 惯例配置文件
│  ├─helper.php                     助手函数文件（可选）
│  ├─LICENSE.txt                    授权说明文件
│  ├─phpunit.xml                    单元测试配置文件
│  ├─README.md                      README 文件
│  └─start.php                      框架引导文件
├─build.php                         自动生成定义文件（参考）
├─composer.json                     composer 定义文件
├─LICENSE.txt                       授权说明文件
├─README.md                         README 文件
└─think                             命令行入口文件
```