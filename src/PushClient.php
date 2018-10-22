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
     * 设备号列表
     * @var array
     */
    protected $cidList;

    /**
     * 别名列表
     * @var array
     */
    protected $aliasList;

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
     * @param $clientId
     * @return $this
     */
    public function clientId($clientId)
    {
        $this->cid = $clientId;
        return $this;
    }

    /**
     * 设置推送别名
     * @param $alias
     * @return $this
     */
    public function alias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * 推送
     * @return bool|mixed
     * @throws ApiException
     */
    public function push()
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
        if ($this->is_single) {
            $this->cid && $res['cid'] = $this->cid;
            $this->alias && $res['alias'] = $this->alias;
            return $this->api->pushSingle($res);
        }
    }

    private function pushSingle()
    {

    }

    private function pushList()
    {

    }

}