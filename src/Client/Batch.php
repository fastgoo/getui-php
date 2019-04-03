<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/25
 * Time: 10:12 AM
 */

namespace GeTui\Client;

use GeTui\ApiException;

class Batch extends Entity
{
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    /**
     * 推送服务
     * 如果设置过条件，那么直接通过条件推送
     * @param bool $is_detail
     * @return bool|mixed
     * @throws ApiException
     */
    public function push($is_detail = false)
    {
        $res = $this->buildRequestData();
        if ($this->conditions) {
            $res['condition'] = $this->conditions;
            $this->reset();
            return $this->api->pushByConditions($res);
        }
        if (!($this->alias && is_array($this->alias)) && !($this->cid && is_array($this->cid))) {
            throw new ApiException('请先设置推送设备ID或者别名');
        }
        $arr = [];
        if ($this->cid) {
            foreach ($this->cid as $value) {
                $res = $this->buildRequestData();
                $res['cid'] = $value;
                $arr[] = $res;
            }
        }
        if ($this->alias) {
            foreach ($this->alias as $value) {
                $res = $this->buildRequestData();
                $res['alias'] = $value;
                $arr[] = $res;
            }
        }
        $this->reset();
        return $this->api->pushSingleBatch($arr, $is_detail);
    }
}