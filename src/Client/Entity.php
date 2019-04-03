<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/23
 * Time: 上午11:18
 */

namespace GeTui\Client;

use GeTui\Api;
use GeTui\ApiException;
use GeTui\Template\Link;
use GeTui\Template\Message;
use GeTui\Template\Notification;
use GeTui\Template\Notypopload;
use GeTui\Template\PushInfo;
use GeTui\Template\Style;

class Entity
{
    private $message;
    private $notification;
    private $pushInfo;
    private $notypopload;
    private $style;
    private $link;
    protected $api;

    protected $config;
    protected $alias;
    protected $cid;
    protected $conditions;

    /**
     * PushClient constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->message = new Message();
        $this->notification = new Notification();
        $this->notypopload = new Notypopload();
        $this->link = new Link();
        $this->style = new Style();
        $this->pushInfo = new PushInfo();
        $this->api = new Api($config);
        $this->config = $config;
    }

    /**
     * 设置message消息结构
     * @param callable $call_func
     * @return $this
     */
    public function setMessage(callable $call_func)
    {
        call_user_func($call_func, $this->message);
        return $this;
    }

    /**
     * 设置应用模板消息结构
     * @param callable $call_func
     * @return $this
     * @throws ApiException
     */
    public function setNotification(callable $call_func)
    {
        if ($this->message->getMsgtype()) {
            throw new ApiException('只可设置一种消息类型的模板');
        }
        $this->message->setMsgtype(($this->message)::MSG_TYPE_NOTIFICATION);
        call_user_func($call_func, $this->notification);
        return $this;
    }

    /**
     * 设置网页模板消息结构
     * @param callable $call_func
     * @return $this
     * @throws ApiException
     */
    public function setLink(callable $call_func)
    {
        if ($this->message->getMsgtype()) {
            throw new ApiException('只可设置一种消息类型的模板');
        }
        $this->message->setMsgtype(($this->message)::MSG_TYPE_LINK);
        call_user_func($call_func, $this->link);
        return $this;
    }

    /**
     * 设置下载模板消息结构
     * @param callable $call_func
     * @return $this
     * @throws ApiException
     */
    public function setNotypopload(callable $call_func)
    {
        if ($this->message->getMsgtype()) {
            throw new ApiException('只可设置一种消息类型的模板');
        }
        $this->message->setMsgtype(($this->message)::MSG_TYPE_NITYPOPLOAD);
        call_user_func($call_func, $this->notypopload);
        return $this;
    }

    /**
     * 设置透传模板消息结构
     * @param callable $call_func
     * @return $this
     * @throws ApiException
     */
    public function setPushInfo(callable $call_func)
    {
        if ($this->message->getMsgtype() && $this->message->getMsgtype() != 'notification') {
            throw new ApiException('只可设置一种消息类型的模板');
        }
        $this->message->setMsgtype(($this->message)::MSG_TYPE_TRANSMISSION);
        call_user_func($call_func, $this->pushInfo);
        return $this;
    }

    /**
     * 设置安卓的通知样式
     * @param callable $call_func
     * @return $this
     */
    public function setStyle(callable $call_func)
    {
        $this->style->setLogourl($this->config['logo_url']);
        call_user_func($call_func, $this->style);
        return $this;
    }

    /**
     * 获取配置信息
     * @param string $type
     * @return array
     */
    public function getStyle($type = 'system')
    {
        return $this->style->getEntity($type);
    }

    /**
     * 设置推送设备号（可传数组）
     * @param $clientId
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->cid = $clientId;
        return $this;
    }

    /**
     * 设置推送别名（可传数组）
     * @param $alias
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * 设置查询条件
     * key 筛选条件类型名称(省市region,手机类型phonetype,用户标签tag)
     * values 筛选参数
     * opt_type 筛选参数的组合，0:取参数并集or，1：交集and，2：相当与not in {参数1，参数2，....}
     * @param array $conditions
     * @return $this
     */
    public function setConditions(array $conditions)
    {
        $this->conditions = $conditions;
        return $this;
    }

    /**
     * 组装推送基础数据数组
     * @return array
     */
    protected function buildRequestData()
    {
        $this->message->setAppKey($this->config['app_key']);
        $res = [
            'message' => $this->message->getEntity(),
            'requestid' => date('YmdHis') . rand(1000, 9999),
        ];
        switch ($this->message->getMsgtype()) {
            case 'notification':
                $res['notification'] = $this->notification->getEntity();
                break;
            case 'link':
                $res['link'] = $this->link->getEntity();
                break;
            case 'notypopload':
                $res['notypopload'] = $this->notypopload->getEntity();
                break;
            case 'transmission':
                $res['transmission'] = $this->notification->getEntity();
                $res['push_info'] = $this->pushInfo->getAlertEntity();
                break;
        }
        return $res;
    }

    public function __destruct()
    {
        //$this->reset();
    }

    /**
     * 获取请求的参数
     * 重置所有配置
     * @return array
     */
    public function getRequestData()
    {
        $res = $this->buildRequestData();
        $this->alias && $res['alias'] = $this->alias;
        $this->cid && $res['cid'] = $this->cid;
        $this->reset();
        return $res;
    }

    /**
     * 重置配置信息
     */
    protected function reset()
    {
        $this->cid = null;
        $this->alias = null;
        $this->conditions = null;
        $this->message = new Message();
        $this->notification = new Notification();
        $this->notypopload = new Notypopload();
        $this->link = new Link();
        $this->style = new Style();
        $this->pushInfo = new PushInfo();
        isset($this->taskId) && $this->taskId = null;
    }
}