<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/22
 * Time: 上午11:07
 */

namespace GeTui;

use GeTui\Client\Batch;
use GeTui\Client\Single;
use GeTui\Client\Task;

class Client
{
    public $api;
    public $single;
    public $batch;
    public $task;

    /**
     * Client constructor.
     * @param array $config
     * @throws ApiException
     */
    public function __construct(array $config)
    {
        if (!$config) {
            throw new ApiException('未设置配置信息');
        }
        $this->api = new Api($config);
        $this->single = new Single($config);
        $this->batch = new Batch($config);
        $this->task = new Task($config);
    }
}