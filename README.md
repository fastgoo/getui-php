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

#### compsoer 安装
`composer require fastgoo/getui-php` 


## 快速开始

```PHP
**
 * @var $api
 * host 请求域名 默认域名https://wxapi.fastgoo.net/
 * timeout 请求超时时间
 * secret 请求key
 */
$api = new \PadChat\Api(['secret' => 'test']);

try {
    /** 初始化微信实例 */
    $res = $api->init('https://webhook.fastgoo.net/callback.php');
    if(!$res){
        exit("微信实例获取失败");
    }
    /** 设置微信实例 */
    $api->setWxHandle($res['wx_user']);
    /** 账号密码/账号手机号/token 登录 */
    $loginRes = $api->login([
        'username' => '你的账号',
        'password' => '你的密码',
        'wx_data' => '不填则安全验证登录'
    ]);
    var_dump($loginRes);
    /** 获取登录二维码 */
    $qrcode = $api->getLoginQrcode();
    if(!$qrcode){
        exit("二维码链接获取失败");
    }
    var_dump($qrcode['url']);
    /** 获取扫码状态 */
    while (true) {
        $qrcodeInfo = $api->getQrcodeStatus();
        if (!$qrcodeInfo) {
            exit();
        }
        var_dump($qrcodeInfo);
        sleep(1);
    }
} catch (\PadChat\RequestException $requestException) {
    var_dump($requestException->getMessage());
}
```





