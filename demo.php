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
$pushClient = new \GeTui\PushClient([
    'app_key' => 'ekbzfpl7S69pPKp32mF3j',
    'app_id' => 'suuFyybjnZAr2GXTJJsJu4',
    'master_secret' => 'kYPL7TDrNG9D6JxFieW2F1',
    'device_token' => '',
    'logo_url' => 'http://dev.img.ybzg.com/static/app/user/getui_logo.png',
]);
try {
    $pushClient->type('ios')
        ->clientId('ed8c552172ceb3dfe8b63266f5de8943')
        ->message([
            'title' => '测试标题',
            'content' => '测试内容',
            'payload' => json_encode(['name' => '周先生'])
        ])
        ->push();
} catch (\GeTui\ApiException $apiException) {

}
