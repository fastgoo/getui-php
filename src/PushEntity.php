<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/22
 * Time: 下午3:47
 */

namespace GeTui;

use GeTui\Template\Link;
use GeTui\Template\Message;
use GeTui\Template\Notification;
use GeTui\Template\Notypopload;
use GeTui\Template\PushInfo;
use GeTui\Template\Style;

class PushEntity
{
    public $message;

    public $notification;
    public $pushInfo;
    public $notypopload;
    public $style;
    public $link;

    public function __construct()
    {
        $this->message = new Message();
        $this->notification = new Notification();
        $this->notypopload = new Notypopload();
        $this->link = new Link();
        $this->style = new Style();
        $this->pushInfo = new PushInfo();
    }

}