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
        $ret = $this->request('POST', $this->baseUri . '/push_single', $params);
        if ($ret['result'] != 'ok') {
            return false;
        }
        return $ret;
    }

    /**
     * 保存推送任务
     * @param array $params
     * @return bool|mixed
     * @throws ApiException
     */
    public function saveTask(array $params)
    {
        $ret = $this->request('POST', $this->baseUri . '/save_list_body', $params);
        if ($ret['result'] != 'ok') {
            return false;
        }
        return $ret;
    }

    /**
     * 推送任务
     * @param array $params
     * @return bool|mixed
     * @throws ApiException
     */
    public function pushTask(array $params)
    {
        $ret = $this->request('POST', $this->baseUri . '/push_list', $params);
        if ($ret['result'] != 'ok') {
            return false;
        }
        return $ret;
    }

    /**
     * 通过条件推送给指定用户群
     * conditions 筛选数组参数
     * key 筛选条件类型名称(省市region,手机类型phonetype,用户标签tag)
     * value 筛选参数
     * opt_type    筛选参数的组合，0:取参数并集or，1：交集and，2：相当与not in {参数1，参数2，....}
     * @param array $params
     * @return bool|mixed
     * @throws ApiException
     */
    public function pushByConditions(array $params)
    {
        $ret = $this->request('POST', $this->baseUri . '/push_app', $params);
        if ($ret['result'] != 'ok') {
            return false;
        }
        return $ret;
    }

    /**
     * 停止推送任务
     * 如果还存在未到达的那么就直接不推送了
     * @param $taskId
     * @return bool|mixed
     * @throws ApiException
     */
    public function stopPush($taskId)
    {
        $ret = $this->request('DELETE', $this->baseUri . '/stop_task/' . $taskId);
        if ($ret['result'] != 'ok') {
            return false;
        }
        return $ret;
    }

    /**
     * 单推批量推送
     * @param array $params
     * @param bool $is_detail
     * @return bool|mixed
     * @throws ApiException
     */
    public function pushSingleBatch(array $params, $is_detail = false)
    {
        $ret = $this->request('POST', $this->baseUri . '/push_single_batch', ['msg_list' => $params, 'need_detail' => $is_detail]);
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