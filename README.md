<p align="center">
<img src="http://www.lgstatic.com/thumbnail_300x300/i/image/M00/4B/5B/Cgp3O1efAWWAC02xAAB2G5qzmHQ810.png?1533850691" width="160" alt="个推 PHP SDK"/>
</p>
<p align="center">
  <a href="https://github.com/fastgoo/getui-php"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg"></a> 
 <a href="https://php.net"><img src="https://img.shields.io/badge/php->=5.6-brightgreen.svg"></a>
 <a href="https://guzzle-cn.readthedocs.io/zh_CN/latest/overview.html"><img src="https://img.shields.io/badge/guzzle->=6.0-green.svg"></a>
 <a href="http://www.symfonychina.com/doc/current/components/cache.html"><img src="https://img.shields.io/badge/symfony/cache->=4.0-green.svg"></a>
  <a href="http://docs.getui.com/getui/server/rest/start/"><img src="https://img.shields.io/badge/个推-sdk-2077ff.svg"></a>
</p>

---
个推文档： http://docs.getui.com/getui/server/rest/start/

:gift: getui-php实现了个推的单推、任务推送、停止推送、批量推以及其他相关接口操作，大大简化了使用流程，同时最大程度还原个推的功能以及设置。

* 推送模板设置
* 推送给单用户
* 投递任务，推送给列表用户
* 批量推送
* 绑定别名
* 别名查询cid列表
* cid查询别名字符串
* 单个cid别名解绑
* 解绑指定别名下的所有cid
* 指定用户设置标签
* 查询指定用户的标签列表
* 添加黑名单
* 移除黑名单
* 查询用户状态
* 获取推送结果
* 获取指定日期的用户统计数据
* 获取指定日期的推送统计数据
* 设置IOS应用的角标数量
* 根据任务组名获取推送结果数据
* 获取当前时间 24小时内在线用户数
* 按条件查询用户数
* 获取可用bi标签
* 获取回执的用户列表

#### compsoer 安装
`composer require fastgoo/getui-php` 


## 快速开始

#### IOS单推送
```PHP
<?php
 
require_once './vendor/autoload.php'; 
 
try {
    $client = new \GeTui\Client([
        'app_key' => 'Xw27x1BaCw6xM0LEoyduM7',
        'app_id' => 'E8oOecFjRt58z1BUAHl2nA',
        'master_secret' => 'VgNT0H5JXtAKrwqJLIXq19',
        'logo_url' => 'http://dev.img.ybzg.com/static/app/user/getui_logo.png',
    ]);
    

    /**
     * 简单IOS的通知单推
     */
    $ret = $client->single->setClientId('')
        ->setPushInfo(function (\GeTui\Template\PushInfo $pushInfo) {
            $pushInfo->setTitle('测试标题');
            $pushInfo->setBody('测试内容');
        })
        ->push();
    var_dump($ret);
    
    
    /**
     * IOS推送详细设置
     */
    $ret = $client->single->setClientId('')
        ->setNotification(function (\GeTui\Template\Notification $notification) {
            /** (安卓有效)收到消息是否立即启动应用，true为立即启动，false则广播等待启动，默认是否 */
            $notification->setTransmissionType(false);
            /** 设置透传的内容 */
            $notification->setTransmissionContent(json_encode(['name' => '周先生']));
            /** 设定展示开始时间，格式为yyyy-MM-dd HH:mm:ss */
            $notification->setDurationBegin();
            /** 设定展示结束时间，格式为yyyy-MM-dd HH:mm:ss */
            $notification->setDurationBegin();
        })
        ->setPushInfo(function (\GeTui\Template\PushInfo $pushInfo) {
            //（必传）通知标题
            $pushInfo->setTitle('测试标题');
            //（必传）通知内容
            $pushInfo->setBody('测试内容');
            //自定义透传内容
            $pushInfo->setPayload(json_encode(['name' => "周先生"]));
            //推送直接带有透传数据 默认1，可选-1
            $pushInfo->setContentAvailable(1);
            //用于计算icon上显示的数字，还可以实现显示数字的自动增减，如“+1”、 “-1”、 “1” 等，计算结果将覆盖badge，默认是+1
            $pushInfo->setAutoBadge('+1');
            //通知铃声文件名，无声设置为“com.gexin.ios.silence”，默认default
            $pushInfo->setSound('default');

            //多媒体设置
            //资源类型（1.图片，2.音频， 3.视频）
            $pushInfo->setType(1);
            //是否只在wifi环境下加载，如果设置成true,但未使用wifi时，会展示成普通通知，默认是false
            $pushInfo->setOnlyWifi(false);
            //多媒体资源地址
            $pushInfo->setUrl('');
        })
        ->push();
    var_dump($ret);
    
    
    
} catch (\GeTui\ApiException $apiException) {

}
```
#### 安卓单推送

```PHP
<?php
 
require_once './vendor/autoload.php'; 
 
try {
    $client = new \GeTui\Client([
        'app_key' => 'Xw27x1BaCw6xM0LEoyduM7',
        'app_id' => 'E8oOecFjRt58z1BUAHl2nA',
        'master_secret' => 'VgNT0H5JXtAKrwqJLIXq19',
        'logo_url' => 'http://dev.img.ybzg.com/static/app/user/getui_logo.png',
    ]);

    /**
     * 别名推送安卓应用推送
     * 这里别名和设备号不可以重复设置，只有其一生效
     */
    $client->single->setAlias('773729704')
        ->setStyle(function (\GeTui\Template\Style $style) {
            /** 设置标题（必传） */
            $style->setTitle('测试标题');
            /** 设置内容（必传） */
            $style->setText('测试内容');
            /** 如果配置中设置过了，那这里就不需要设置，如果设置了将覆盖配置的url */
            $style->setLogourl('');
            /** 设置logo,这个是APP本地文件的logo名称，一般不会设置，直接使用logo_url */
            $style->setLogo('');

            /** 通知是否可清除： true可清除，false不可清除。默认可清除 */
            $style->setIsClearable(true);
            /** 收到通知是否振动：true振动，false不振动。默认振动 */
            $style->setIsVibrate(true);
            /** 收到通知是否响铃：true响铃，false不响铃。默认响铃 */
            $style->setIsRing(true);

            /** 背景图样式，设置背景图的url地址 */
            $style->setBannerUrl('');

            /** 展开通知样式，设置类型
             * 1 通知展示大图样式，参数是大图的URL地址
             * 2 通知展示文本+长文本样式，参数是长文本
             * 3 通知展示大图+小图样式，参数是大图URL和小图URL
             */
            $style->setBigStyle(1);
            //展开通知样式 1
            $style->setBigImageUrl('');
            //展开通知样式 2
            $style->setBigText('');
            //展开通知样式 3，这个是大图和背景图的2个参数组合
            //$style->setBigImageUrl('');
            //$style->setBannerUrl('');

        });
    /** 打开应用模板消息（模板消息只可以设置一种） */
    $client->single->setNotification(function (\GeTui\Template\Notification $notification) use ($client) {
        //收到消息是否立即启动应用，true为立即启动，false则广播等待启动，默认是否
        $notification->setTransmissionType(true);
        //（可空）透传字符串
        $notification->setTransmissionContent(json_encode(['name' => '周先生']));
        //（必传）设置样式，结合Style的设置获取指定的样式类型：system(个推),getui(个推),banner(背景图),expand(展开)
        $notification->setStyle($client->single->getStyle());
        //设定展示开始时间，格式为yyyy-MM-dd HH:mm:ss
        $notification->setDurationBegin('2018-05-06 12:00:00');
        //设定展示结束时间，格式为yyyy-MM-dd HH:mm:ss
        $notification->setDurationEnd('2018-05-08 12:00:00');
    });
    /** 打开网页模板消息（模板消息只可以设置一种） */
    $client->single->setLink(function (\GeTui\Template\Link $link) use ($client) {
        //（必传）打开网址的地址
        $link->setUrl('');
        //（必传）设置样式，结合Style的设置获取指定的样式类型：system(个推),getui(个推),banner(背景图),expand(展开)
        $link->setStyle($client->single->getStyle());
        //设定展示开始时间，格式为yyyy-MM-dd HH:mm:ss
        $link->setDurationBegin('2018-05-06 12:00:00');
        //设定展示结束时间，格式为yyyy-MM-dd HH:mm:ss
        $link->setDurationEnd('2018-05-08 12:00:00');
    });
    /** 弹窗下模板消息（模板消息只可以设置一种） */
    $client->single->setNotypopload(function (\GeTui\Template\Notypopload $notypopload) use ($client) {
        //（必传）设置样式，结合Style的设置获取指定的样式类型：system(个推),getui(个推),banner(背景图),expand(展开)
        $notypopload->setStyle($client->single->getStyle());
        //（必传）通知栏图标
        $notypopload->setNotyicon();
        //（必传）通知标题
        $notypopload->setNotytitle();
        //（必传）通知内容
        $notypopload->setNotycontent();
        //（必传）弹出框标题
        $notypopload->setPoptitle();
        //（必传）弹出框内容
        $notypopload->setPopcontent();
        //（必传）弹出框图标
        $notypopload->setPopimage();
        //（必传）弹出框左边按钮名称
        $notypopload->setPopbutton1();
        //（必传）弹出框右边按钮名称
        $notypopload->setPopbutton2();
        //现在图标
        $notypopload->setLoadicon();
        //下载标题
        $notypopload->setLoadtitle();
        //（必传）下载文件地址
        $notypopload->setLoadurl();
        //是否自动安装，默认值false
        $notypopload->setIsAutoinstall();
        //安装完成后是否自动启动应用程序，默认值false
        $notypopload->setIsActived();
        //设定展示开始时间，格式为yyyy-MM-dd HH:mm:ss
        $notypopload->setDurationBegin('2018-05-06 12:00:00');
        //设定展示结束时间，格式为yyyy-MM-dd HH:mm:ss
        $notypopload->setDurationEnd('2018-05-08 12:00:00');
    });

    /** 推送消息 */
    $ret = $client->single->push();
    
    var_dump($ret);

} catch (\GeTui\ApiException $apiException) {

}
```

#### 任务投递推送
```PHP
<?php
 
 require_once './vendor/autoload.php';
 
 try {
     $client = new \GeTui\Client([
         'app_key' => 'Xw27x1BaCw6xM0LEoyduM7',
         'app_id' => 'E8oOecFjRt58z1BUAHl2nA',
         'master_secret' => 'VgNT0H5JXtAKrwqJLIXq19',
         'logo_url' => 'http://dev.img.ybzg.com/static/app/user/getui_logo.png',
     ]);
     /**
      * 任务投递推送
      * 这里的推送模板设置，
      * 设置别名和设备号只可以选一种类型去推送，不可二者都选
      * 这里的save是把任务保存在个推服务器
      */
     $client->task->setClientId(['设备号1', '设备号2', '设备号3'])
         ->setPushInfo(function (\GeTui\Template\PushInfo $pushInfo) {
             $pushInfo->setTitle('测试标题');
             $pushInfo->setBody('测试内容');
         })
         ->save();
     //获取推送的任务ID,如果需要保存taskId的话，可以用这种方式获取,不过基本上没啥用处
     $taskId = $client->task->taskId;
     //执行推送操作，推送到指定的设备列表,传入参数是否展示推送任务的详情，默认false不展示
     $client->task->run(false);
 
 } catch (\GeTui\ApiException $apiException) {
 
 }
```

#### 根据个推条件批量推送
```PHP
<?php
 
 require_once './vendor/autoload.php';
 
 try {
     $client = new \GeTui\Client([
         'app_key' => 'Xw27x1BaCw6xM0LEoyduM7',
         'app_id' => 'E8oOecFjRt58z1BUAHl2nA',
         'master_secret' => 'VgNT0H5JXtAKrwqJLIXq19',
         'logo_url' => 'http://dev.img.ybzg.com/static/app/user/getui_logo.png',
     ]);
     $client->batch->setPushInfo(function (\GeTui\Template\PushInfo $pushInfo) {
         $pushInfo->setTitle('测试标题');
         $pushInfo->setBody('测试内容');
     });
     /**
      * key 筛选条件类型名称(省市region,手机类型phonetype,用户标签tag)
      * value 筛选参数
      * opt_type 筛选参数的组合，0:取参数并集or，1：交集and，2：相当与not in {参数1，参数2，....}
      */
     $client->batch->setConditions([
         ["key" => "phonetype", "values" => ["ANDROID"], "opt_type" => 0],//条件设备类型为安卓设备
         ["key" => "region", "values" => ["11000000", "12000000"], "opt_type" => 0],//指定区域
         ["key" => "tag", "values" => ["usertag"], "opt_type" => 0]//指定用户标签
     ]);
     //开始推送给指定的筛选条件，只个条件是个推的服务器条件
     $client->batch->push();
 
 } catch (\GeTui\ApiException $apiException) {
 
 }
```

#### api接口调用
```PHP
<?php
 
 require_once './vendor/autoload.php';
 
 try {
     $client = new \GeTui\Client([
         'app_key' => 'Xw27x1BaCw6xM0LEoyduM7',
         'app_id' => 'E8oOecFjRt58z1BUAHl2nA',
         'master_secret' => 'VgNT0H5JXtAKrwqJLIXq19',
         'logo_url' => 'http://dev.img.ybzg.com/static/app/user/getui_logo.png',
     ]);
     /**
      * api实现了个推推送接口的全部操作
      * 传入参数请参考文档地址的参数
      * 基本上传入的参数都是基本一致的，有的数组参数会做一个基础的封装
      * 例如绑定别名的操作接口
      */
     $ret = $client->api->bindAlias([
         ['cid' => '1', 'alias' => '11'],
         ['cid' => '2', 'alias' => '22'],
     ]);
 
 
 } catch (\GeTui\ApiException $apiException) {
 
 }
```





