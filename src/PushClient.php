<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/22
 * Time: 上午11:07
 */

namespace GeTui;

class PushClient
{

    /**
     * api实例
     * @var Api
     */
    protected $api;

    /**
     * 配置
     * @var array
     */
    protected $config;

    /**
     * 是否是推送给单个用户
     * @var bool
     */
    protected $is_single = true;

    /**
     * 推送模板实例
     * @var PushEntity
     */
    protected $entity;

    /**
     * 推送类型 ios android
     * @var string
     */
    protected $type;

    /**
     * 设备号
     * @var string
     */
    protected $cid;

    /**
     * 别名
     * @var string
     */
    protected $alias;

    /**
     * 推送任务ID，主要用于群推
     * @var string
     */
    protected $taskId;

    /**
     * PushClient constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->api = new Api($config);
        $this->config = $config;
        $this->entity = new PushEntity();
    }

    /**
     * 设置离线推送策略
     * @param bool $is_offline
     * @param float|int $expire_time
     * @return $this
     */
    public function offline($is_offline = true, $expire_time = 3600 * 24 * 30)
    {
        $this->entity->message->setIsOffline($is_offline);
        $this->entity->message->setExpireTime($expire_time);
        return $this;
    }

    /**
     *
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 基础消息
     * @param array $data
     * @return $this
     * @throws ApiException
     */
    public function message(array $data)
    {
        if (!$this->type) {
            throw new ApiException('请先设置推送类型，ios or android');
        }
        $message = $this->entity->message;
        if ($this->type == 'ios') {
            $this->entity->message->setMsgtype($message::MSG_TYPE_TRANSMISSION);
            $this->entity->pushInfo->setTitle($data['title']);
            $this->entity->pushInfo->setBody($data['content']);
            !empty($data['payload']) && $this->entity->pushInfo->setPayload($data['payload']);
        } else {
            $this->entity->style->setLogourl($this->config['logo_url']);
            $this->entity->style->setTitle($data['title']);
            $this->entity->style->setText($data['content']);
            $this->entity->notification->setStyle($this->entity->style->getGeTuiEntity());
            $this->entity->message->setMsgtype($message::MSG_TYPE_NOTIFICATION);
        }
        //!empty($data['is_wakeup']) && $this->entity->notification->setTransmissionType($data['is_wakeup']);
        !empty($data['payload']) && $this->entity->notification->setTransmissionContent($data['payload']);
        !empty($data['duration_begin']) && $this->entity->notification->setDurationBegin($data['duration_begin']);
        !empty($data['duration_end']) && $this->entity->notification->setDurationBegin($data['duration_end']);
        return $this;
    }

    /**
     * 设置推送设备号
     * 可设置数组
     * @param $clientId
     * @return $this
     */
    public function clientId($clientId)
    {
        $this->cid = $clientId;
        is_array($this->cid) && $this->is_single = false;
        return $this;
    }

    /**
     * 设置推送别名
     * 可设置数组
     * @param $alias
     * @return $this
     */
    public function alias($alias)
    {
        $this->alias = $alias;
        is_array($this->alias) && $this->is_single = false;
        return $this;
    }

    /**
     * 推送
     * @return bool|mixed
     * @throws ApiException
     */
    public function push()
    {
        $res = $this->buildRequestData();
        if ($this->is_single) {
            $this->cid && $res['cid'] = $this->cid;
            $this->alias && $res['alias'] = $this->alias;
            return $this->api->pushSingle($res);
        }
    }

    /**
     * 保存推送任务
     * @return $this
     * @throws ApiException
     */
    public function save()
    {
        $ret = $this->api->saveTask($this->buildRequestData());
        if (!$ret) {
            throw new ApiException('推送消息任务投递失败了');
        }
        $this->taskId = $ret['taskid'];
        return $this;
    }

    /**
     * 开始推送任务
     * @param bool $is_detail
     * @throws ApiException
     */
    public function pushTask($is_detail = false)
    {
        if (!$this->taskId) {
            throw new ApiException('taskid不存在');
        }
        $res = ['need_detail' => $is_detail, 'taskid' => $this->taskId,];
        if ($this->is_single) {
            throw new ApiException('推送对象非列表');
        }
        $this->alias && $res['alias'] = $this->alias;
        $this->cid && $res['cid'] = $this->cid;
        $this->api->pushTask($res);
    }

    /**
     * 通过筛选条件推送消息
     * @param array $conditions
     * @return bool|mixed
     * @throws ApiException
     */
    public function pushByCondition(array $conditions)
    {
        $res = $this->buildRequestData();
        $res['condition'] = $conditions;
        return $this->api->pushByConditions($res);
    }

    public function pushBatch()
    {

    }

    /**
     * 停止推送任务
     * @param $taskId
     * @return bool|mixed
     * @throws ApiException
     */
    public function stop($taskId)
    {
        return $this->api->stopPush($taskId);
    }

    /**
     * 组装推送基础数据数组
     * @return array
     */
    private function buildRequestData()
    {
        $this->entity->message->setAppKey($this->config['app_key']);
        $res = [
            'message' => $this->entity->message->getEntity(),
            'requestid' => date('YmdHis') . rand(1000, 9999),
        ];
        switch ($this->entity->message->getMsgtype()) {
            case 'notification':
                $res['notification'] = $this->entity->notification->getEntity();
                break;
            case 'link':
                $res['link'] = $this->entity->link->getEntity();
                break;
            case 'notypopload':
                $res['notypopload'] = $this->entity->notypopload->getEntity();
                break;
            case 'transmission':
                $res['transmission'] = $this->entity->notification->getEntity();
                $res['push_info'] = $this->entity->pushInfo->getAlertEntity();
                break;
        }
        return $res;
    }

    /**
     * 初始化对象
     * 以便以下次数据不会被污染
     */
    private function clear()
    {
        $this->entity = new PushEntity();
        $this->taskId = null;
        $this->is_single = true;
        $this->cid = null;
        $this->alias = null;
        $this->type = null;
    }


    private function pushSingle()
    {

    }

    private function pushList()
    {

    }

}