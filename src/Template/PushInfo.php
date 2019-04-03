<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/22
 * Time: 下午2:44
 */

namespace GeTui\Template;

class PushInfo
{
    /**
     * ios的推送机制
     * @var array
     */
    protected $aps;

    /**
     * 弹窗
     * @var array
     */
    protected $alert;

    /**
     * 通知文本消息
     * @var string
     */
    protected $body;

    /**
     * 用于多语言支持）指定执行按钮所使用的Localizable.strings
     * @var string
     */
    protected $action_loc_key;

    /**
     * 用于多语言支持）指定Localizable.strings文件中相应的key
     * @var string
     */
    protected $loc_key;

    /**
     * 如果loc-key中使用了占位符，则在loc-args中指定各参数
     * @var string
     */
    protected $loc_args;

    /**
     * 指定启动界面图片名
     * @var string
     */
    protected $launch_image;

    /**
     * 通知标题
     * @var string
     */
    protected $title;

    /**
     * (用于多语言支持）对于标题指定执行按钮所使用的Localizable.strings,仅支持iOS8.2以上版本
     * @var string
     */
    protected $titile_loc_key;

    /**
     * 对于标题,如果loc-key中使用的占位符，则在loc-args中指定各参数,仅支持iOS8.2以上版本
     * @var string
     */
    protected $title_loc_args;

    /**
     * 通知子标题,仅支持iOS8.2以上版本
     * @var string
     */
    protected $subtitle;

    /**
     * 当前本地化文件中的子标题字符串的关键字,仅支持iOS8.2以上版本
     * @var string
     */
    protected $subtitle_loc_key;

    /**
     * 当前本地化子标题内容中需要置换的变量参数 ,仅支持iOS8.2以上版本
     * @var string
     */
    protected $subtitle_loc_args;

    /**
     * 用于计算icon上显示的数字，还可以实现显示数字的自动增减，如“+1”、 “-1”、 “1” 等，计算结果将覆盖badge
     * @var string
     */
    protected $auto_badge = '+1';

    /**
     * 通知铃声文件名，无声设置为“com.gexin.ios.silence”
     * @var string
     */
    protected $sound = 'default';

    /**
     * 推送直接带有透传数据 -1 1
     * @var integer
     */
    protected $content_available = 1;

    /**
     * 在客户端通知栏触发特定的action和button显示
     * @var string
     */
    protected $category;

    /**
     * 透传，自定义数据
     * @var string
     */
    protected $payload;

    /**
     * 多媒体
     * @var array
     */
    protected $multimedia;

    /**
     * 多媒体资源地址
     * @var string
     */
    protected $url;

    /**
     * 资源类型（1.图片，2.音频， 3.视频）
     * @var int
     */
    protected $type;

    /**
     * 是否只在wifi环境下加载，如果设置成true,但未使用wifi时，会展示成普通通知
     * @var bool
     */
    protected $only_wifi = false;

    /**
     * 获取弹窗通知实体
     * @return array
     */
    public function getAlertEntity()
    {
        $res = [
            'aps' => [
                'alert' => [
                    "title" => $this->title,
                    "body" => $this->body,
                ],
                'autoBadge' => $this->auto_badge,
                'content-available' => $this->content_available,
                'sound' => $this->sound,
            ],
        ];
        $this->url && $res['aps']['multimedia'] = [
            'type' => $this->type,
            'url' => $this->url,
            'only_wifi' => $this->only_wifi,
        ];
        $this->payload && $res['payload'] = $this->payload;
        return $res;
    }

    /**
     * 获取单透传通知实体
     * @return array
     */
    public function getPayloadEntity()
    {
        return ['payload' => $this->payload];
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $auto_badge
     */
    public function setAutoBadge($auto_badge)
    {
        $this->auto_badge = $auto_badge;
    }

    /**
     * @param string $sound
     */
    public function setSound($sound)
    {
        $this->sound = $sound;
    }

    /**
     * @param int $content_available
     */
    public function setContentAvailable($content_available)
    {
        $this->content_available = $content_available;
    }

    /**
     * @param string $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param bool $only_wifi
     */
    public function setOnlyWifi($only_wifi)
    {
        $this->only_wifi = $only_wifi;
    }
}