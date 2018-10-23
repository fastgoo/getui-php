<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/23
 * Time: 上午10:22
 */

namespace GeTui\Client;

use GeTui\ApiException;

class Task extends Entity
{
    protected $taskId;

    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    /**
     * 执行推送服务
     * @param bool $is_detail
     * @return bool|mixed
     * @throws ApiException
     */
    public function run($is_detail = false)
    {
        if (!$this->taskId) {
            throw new ApiException('taskid不存在');
        }
        $res = ['need_detail' => $is_detail, 'taskid' => $this->taskId,];
        if ($this->alias && is_array($this->alias)) {
            $res['alias'] = $this->alias;
        }
        if ($this->cid && is_array($this->cid)) {
            $res['cid'] = $this->cid;
        }
        if (empty($res['cid'])) {
            throw new ApiException('推送对象非列表');
        }
        return $this->api->pushTask($res);
    }

    /**
     * 保存推送任务
     * @throws \GeTui\ApiException
     */
    public function save()
    {
        $ret = $this->api->saveTask($this->buildRequestData());
        if (!$ret) {
            throw new ApiException('个推任务保存失败了。ret: ' . json_encode($ret, JSON_UNESCAPED_UNICODE));
        }
        $this->taskId = $ret['taskid'];
        return $this;
    }
}
