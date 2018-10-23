<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/22
 * Time: 下午2:14
 */

namespace GeTui\Template;

class Link
{
    /**
     * 通知栏消息布局样式，详见Style说明
     * @var array
     */
    protected $style = [];

    /**
     * 打开网址
     * @var string
     */
    protected $url;

    /**
     * 设定展示开始时间，格式为yyyy-MM-dd HH:mm:ss
     * @var string
     */
    protected $duration_begin;

    /**
     * 设定展示结束时间，格式为yyyy-MM-dd HH:mm:ss
     * @var string
     */
    protected $duration_end;

    /**
     * 获取应用模板
     * @return array
     */
    public function getEntity()
    {
        $res = [
            'style' => $this->style,
            'url' => $this->url
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
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
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