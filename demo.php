<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/19
 * Time: 下午4:44
 */
require_once './vendor/autoload.php';

/*$api = new \GeTui\Api([
    'app_key' => 'ekbzfpl7S69pPKp32mF3j',
    'app_id' => 'suuFyybjnZAr2GXTJJsJu4',
    'master_secret' => 'kYPL7TDrNG9D6JxFieW2F1',
    'device_token' => '',
    'logo_url' => 'http://dev.img.ybzg.com/static/app/user/getui_logo.png',
]);*/
$pushClient = new \GeTui\Client\Single([
    'app_key' => 'ekbzfpl7S69pPKp32mF3j',
    'app_id' => 'suuFyybjnZAr2GXTJJsJu4',
    'master_secret' => 'kYPL7TDrNG9D6JxFieW2F1',
    'device_token' => '',
    'logo_url' => 'http://dev.img.ybzg.com/static/app/user/getui_logo.png',
]);
try {
    $pushClient->setClientId('ed8c552172ceb3dfe8b63266f5de8943');
    $pushClient->setPushInfo(function (\GeTui\Template\PushInfo $pushInfo) {
        $pushInfo->setTitle('测试标题');
        $pushInfo->setBody('测试内容');
    });
    var_dump($pushClient->push());

    $pushClient->setStyle(function (\GeTui\Template\Style $style) {
        $style->setTitle('测试标题');
        $style->setText('测试内容');
    });
    $pushClient->setClientId('6487cb21f19a1c3542120af66683a0d8');
    $pushClient->setNotification(function (\GeTui\Template\Notification $notification) use ($pushClient) {
        $notification->setStyle($pushClient->getStyle());
    });
    var_dump($pushClient->push());

    /*$pushClient->type('android')
        ->clientId('ed8c552172ceb3dfe8b63266f5de8943')
        ->message([
            'title' => '测试标题',
            'content' => '测试内容',
            'payload' => json_encode(['name' => '周先生'])
        ])
        ->push();*/
} catch (\GeTui\ApiException $apiException) {
    var_dump($apiException->getMessage());
}
