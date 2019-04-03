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
        'app_key' => '',
        'app_id' => '',
        'master_secret' => '',
        'logo_url' => '',
    ]);


} catch (\GeTui\ApiException $apiException) {

}
