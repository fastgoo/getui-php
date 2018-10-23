<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/22
 * Time: 下午2:28
 */

namespace GeTui\Template;

class Notypopload
{
    /**
     * 通知栏消息布局样式，详见Style说明
     * @var array
     */
    protected $style;

    /**
     * 通知栏图标 (必传)
     * @var string
     */
    protected $notyicon;

    /**
     * 通知标题 (必传)
     * @var string
     */
    protected $notytitle;

    /**
     * 通知内容 (必传)
     * @var string
     */
    protected $notycontent;

    /**
     * 弹出框标题 (必传)
     * @var string
     */
    protected $poptitle;

    /**
     * 弹出框内容 (必传)
     * @var string
     */
    protected $popcontent;

    /**
     * 弹出框图标 (必传)
     * @var string
     */
    protected $popimage;

    /**
     * 弹出框左边按钮名称 (必传)
     * @var string
     */
    protected $popbutton1;

    /**
     * 弹出框右边按钮名称 (必传)
     * @var string
     */
    protected $popbutton2;

    /**
     * 下载图标
     * @var string
     */
    protected $loadicon;

    /**
     * 下载标题
     * @var string
     */
    protected $loadtitle;

    /**
     * 下载文件地址 (必传)
     * @var string
     */
    protected $loadurl;

    /**
     * 是否自动安装，默认值false
     * @var bool
     */
    protected $is_autoinstall = false;

    /**
     * 安装完成后是否自动启动应用程序，默认值false
     * @var bool
     */
    protected $is_actived = false;

    /**
     * 安装完成后是否自动启动应用程序，默认值false
     * @var string
     */
    protected $duration_begin;

    /**
     * 设定展示结束时间，格式为yyyy-MM-dd HH:mm:ss
     * @var string
     */
    protected $duration_end;

    /**
     * 安卓标识
     * @var string
     */
    protected $androidmark;

    /**
     * 塞班标识
     * @var string
     */
    protected $symbianmark;

    /**
     * 苹果标识
     * @var string
     */
    protected $iphonemark;

    public function getEntity()
    {
        $res = [
            'style' => $this->style,
            'notyicon' => $this->notyicon,
            'notytitle' => $this->notytitle,
            'notycontent' => $this->notycontent,
            'poptitle' => $this->poptitle,
            'popcontent' => $this->popcontent,
            'popimage' => $this->popimage,
            'popbutton1' => $this->popbutton1,
            'popbutton2' => $this->popbutton2,
            'loadicon' => $this->loadicon,
            'loadtitle' => $this->loadtitle,
            'loadurl' => $this->loadurl,
            'is_autoinstall' => $this->is_autoinstall,
            'is_actived' => $this->is_actived,
        ];
        $this->duration_begin && $res['duration_begin'] = $this->duration_begin;
        $this->duration_end && $res['duration_end'] = $this->duration_end;
        return $res;
    }

    /**
     * @param array $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * @param string $notyicon
     */
    public function setNotyicon($notyicon)
    {
        $this->notyicon = $notyicon;
    }

    /**
     * @param string $notytitle
     */
    public function setNotytitle($notytitle)
    {
        $this->notytitle = $notytitle;
    }

    /**
     * @param string $notycontent
     */
    public function setNotycontent($notycontent)
    {
        $this->notycontent = $notycontent;
    }

    /**
     * @param string $poptitle
     */
    public function setPoptitle($poptitle)
    {
        $this->poptitle = $poptitle;
    }

    /**
     * @param string $popcontent
     */
    public function setPopcontent($popcontent)
    {
        $this->popcontent = $popcontent;
    }

    /**
     * @param string $popimage
     */
    public function setPopimage($popimage)
    {
        $this->popimage = $popimage;
    }

    /**
     * @param string $popbutton1
     */
    public function setPopbutton1($popbutton1)
    {
        $this->popbutton1 = $popbutton1;
    }

    /**
     * @param string $popbutton2
     */
    public function setPopbutton2($popbutton2)
    {
        $this->popbutton2 = $popbutton2;
    }

    /**
     * @param string $loadicon
     */
    public function setLoadicon($loadicon)
    {
        $this->loadicon = $loadicon;
    }

    /**
     * @param string $loadtitle
     */
    public function setLoadtitle($loadtitle)
    {
        $this->loadtitle = $loadtitle;
    }

    /**
     * @param string $loadurl
     */
    public function setLoadurl($loadurl)
    {
        $this->loadurl = $loadurl;
    }

    /**
     * @param bool $is_autoinstall
     */
    public function setIsAutoinstall($is_autoinstall)
    {
        $this->is_autoinstall = $is_autoinstall;
    }

    /**
     * @param bool $is_actived
     */
    public function setIsActived($is_actived)
    {
        $this->is_actived = $is_actived;
    }

    /**
     * @param string $duration_begin
     */
    public function setDurationBegin($duration_begin)
    {
        $this->duration_begin = $duration_begin;
    }

    /**
     * @param string $duration_end
     */
    public function setDurationEnd($duration_end)
    {
        $this->duration_end = $duration_end;
    }
}