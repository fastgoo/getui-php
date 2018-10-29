<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/19
 * Time: 下午4:44
 */
require_once './vendor/autoload.php';

try {
    $client = new \GeTui\Client([
        'app_key' => 'Xw27x1BaCw6xM0LEoyduM7',
        'app_id' => 'E8oOecFjRt58z1BUAHl2nA',
        'master_secret' => 'VgNT0H5JXtAKrwqJLIXq19',
        'logo_url' => 'http://dev.img.ybzg.com/static/app/user/getui_logo.png',
    ]);


} catch (\GeTui\ApiException $apiException) {

}