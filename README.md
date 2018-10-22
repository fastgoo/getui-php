
### 扩展简介

---

* 该扩展主要是通过aliyun官方 2017-05-25 版本云短信封装的php扩展包
* 目前主要支持2个功能接口（发送短信、获取指定号码指定时间的短信记录）
* 该扩展适用于目前所有现代框架
* 作者qq 773729704、微信 huoniaojugege  加好友备注github

### 安装

---

```
composer require fastgoo/aliyun-sms   


或者手动引入到composer.json

{
  "require": {
    "fastgoo/aliyun-sms": "1.0"
  }
}

composer install #安装依赖
```

### 使用范例

---

```
<?php

use Aliyun\Sms\Api AS SmsApi;

/** 短信推送配置信息 **/
$config = [
    'accessKeyId' => '你的accessKeyId',
    'accessKeySecret' => '你的accessKeySecret',
    'signName' => '签名名称',
    'defaultTemplate' => '默认模板code',
];

$smsApi = new SmsApi($config);

//例如模板code的模板内容为：您的验证码为：${code}，该验证码 5 分钟内有效，请勿泄漏于他人。
$templateCode = "模板code";

//模板参数 code为模板内容里面的变量
$param = ['code'=>'123456'];
$phone = '手机号码';
$result = $smsApi->setTemplate($param,$templateCode)->send($phone);
```

### 获取指定号码的发送记录

---

```
$date = "20170801"; 查询指定时间范围内的发送记录
$nums = 15; #每页15条数据
$page = 1; #当前页码
$result = $smsApi->getSendDetail($phone,$date,$nums,$page)
```


