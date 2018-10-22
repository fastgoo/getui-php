<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/19
 * Time: 下午3:04
 */

namespace GeTui;

class Api
{
    use ClientManage;

    public function __construct(array $config = [])
    {
        $this->init($config);
    }

    /**
     * 推送给单个用户
     * @param array $params
     * @return bool|mixed
     * @throws ApiException
     */
    public function pushSingle(array $params)
    {
        //var_dump($params);exit;
        $ret = $this->request('POST', $this->baseUri . '/push_single', $params);
        //var_dump($ret);exit;
        if ($ret['result'] != 'ok') {
            return false;
        }
        return $ret;
    }

    public function bindAlias()
    {

    }

    public function getClientIdsByAlias()
    {

    }

    public function getAliaByClientId()
    {

    }

    public function unbindAlias()
    {

    }

    public function unbindAliasAll()
    {

    }

    public function setTags()
    {

    }

    public function getTags()
    {

    }

    public function getBlkList()
    {

    }
}