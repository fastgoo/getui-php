<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/23
 * Time: 上午11:18
 */

namespace GeTui\Client;

use GeTui\Api;
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
     */
    public function setMessage(callable $call_func)
    {
        call_user_func($call_func, $this->message);
    }

    /**
     * 设置应用模板消息结构
     * @param callable $call_func
     */
    public function setNotification(callable $call_func)
    {
        $this->message->setMsgtype(($this->message)::MSG_TYPE_NOTIFICATION);
        call_user_func($call_func, $this->notification);
    }

    /**
     * 设置网页模板消息结构
     * @param callable $call_func
     */
    public function setLink(callable $call_func)
    {
        $this->message->setMsgtype(($this->message)::MSG_TYPE_LINK);
        call_user_func($call_func, $this->link);
    }

    /**
     * 设置下载模板消息结构
     * @param callable $call_func
     */
    public function setNotypopload(callable $call_func)
    {
        $this->message->setMsgtype(($this->message)::MSG_TYPE_NITYPOPLOAD);
        call_user_func($call_func, $this->notypopload);
    }

    /**
     * 设置透传模板消息结构
     * @param callable $call_func
     */
    public function setPushInfo(callable $call_func)
    {
        $this->message->setMsgtype(($this->message)::MSG_TYPE_TRANSMISSION);
        call_user_func($call_func, $this->pushInfo);
    }

    /**
     * 设置安卓的通知样式
     * @param callable $call_func
     * @return mixed
     */
    public function setStyle(callable $call_func)
    {
        $this->style->setLogourl($this->config['logo_url']);
        call_user_func($call_func, $this->style);
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
     */
    public function setClientId($clientId)
    {
        $this->cid = $clientId;
    }

    /**
     * 设置推送别名（可传数组）
     * @param $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
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
     * 重置配置信息
     */
    protected function reset()
    {
        $this->cid = null;
        $this->alias = null;
        $this->message = new Message();
        $this->notification = new Notification();
        $this->notypopload = new Notypopload();
        $this->link = new Link();
        $this->style = new Style();
        $this->pushInfo = new PushInfo();
    }
}