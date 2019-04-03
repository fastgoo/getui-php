<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/19
 * Time: 下午3:04
 */

namespace GeTui;

/**
 * Class Api
 *
 * @package GeTui
 * 其他接口文档地址：http://docs.getui.com/getui/server/rest/other_if/
 * 推送接口文档地址：http://docs.getui.com/getui/server/rest/push/
 */
class Api
{
    use ClientManage;
    private $apiRes;

    public function __construct(array $config = [])
    {
        $this->apiRes = [];
        $this->init($config);
    }

    /**
     * 获取查询结果集
     *
     * @return array
     */
    public function getApiRes()
    {
        return $this->apiRes;
    }

    /**
     * @param $method
     * @param $url
     * @param array $params
     * @return array|mixed
     * @throws ApiException
     */
    private function requestCal($method, $url, $params = [])
    {
        $this->apiRes = $this->request($method, $url, $params);
        return $this->apiRes;
    }

    /**
     * 推送给单个用户
     *
     * @param array $params
     * @return bool|mixed
     * @throws ApiException
     */
    public function pushSingle(array $params)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/push_single', $params);
        if ($ret['result'] != 'ok') {
            return false;
        }
        return $ret;
    }

    /**
     * 保存推送任务
     *
     * @param array $params
     * @return bool|mixed
     * @throws ApiException
     */
    public function saveTask(array $params)
    {
        unset($params['requestid']);
        $ret = $this->requestCal('POST', $this->baseUri . '/save_list_body', $params);
        if ($ret['result'] != 'ok') {
            return false;
        }
        return $ret['taskid'];
    }

    /**
     * 推送任务
     *
     * @param array $params
     * @return bool|mixed
     * @throws ApiException
     */
    public function pushTask(array $params)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/push_list', $params);
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
     *
     * @param array $params
     * @return bool|mixed
     * @throws ApiException
     */
    public function pushByConditions(array $params)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/push_app', $params);
        if ($ret['result'] != 'ok') {
            return false;
        }
        return $ret;
    }

    /**
     * 停止推送任务
     * 如果还存在未到达的那么就直接不推送了
     *
     * @param $taskId
     * @return bool|mixed
     * @throws ApiException
     */
    public function stopPush($taskId)
    {
        $ret = $this->requestCal('DELETE', $this->baseUri . '/stop_task/' . $taskId);
        if ($ret['result'] != 'ok') {
            return false;
        }
        return $ret;
    }

    /**
     * 单推批量推送
     *
     * @param array $params
     * @param bool $is_detail
     * @return bool|mixed
     * @throws ApiException
     */
    public function pushSingleBatch(array $params, $is_detail = false)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/push_single_batch', ['msg_list' => $params, 'need_detail' => $is_detail]);
        if ($ret['result'] != 'ok') {
            return false;
        }
        return $ret;
    }

    /**
     * 绑定别名，可以批量绑定
     *
     * @param array $params
     * @return bool|mixed
     * @throws ApiException
     */
    public function bindAlias(array $params)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/bind_alias', ['alias_list' => $params]);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return true;
    }

    /**
     * 根据别名获取已绑定的设备号列表
     * 一个别名可以对应N个设备号
     *
     * @param $alias
     * @return bool|mixed
     * @throws ApiException
     */
    public function getClientIdsByAlias($alias)
    {
        $ret = $this->requestCal('GET', $this->baseUri . '/query_cid/' . $alias);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return $ret['cid'];
    }

    /**
     * 根据设备号获取别名字符串
     *
     * @param $clientId
     * @return bool
     * @throws ApiException
     */
    public function getAliaByClientId($clientId)
    {
        $ret = $this->requestCal('GET', $this->baseUri . '/query_alias/' . $clientId);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return $ret['alias'];
    }

    /**
     * 设备号别名解绑
     *
     * @param $clientId
     * @param $alias
     * @return bool
     * @throws ApiException
     */
    public function unbindAlias($clientId, $alias)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/unbind_alias', ['cid' => $clientId, 'alias' => $alias]);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return true;
    }

    /**
     * 解绑该别名相关的所有设备号
     *
     * @param $alias
     * @return bool
     * @throws ApiException
     */
    public function unbindAliasAll($alias)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/unbind_alias_all', compact('alias'));
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return true;
    }

    /**
     * 设置用户标签
     *
     * @param $clientId
     * @param $tags
     * @return bool
     * @throws ApiException
     */
    public function setTags($clientId, $tags)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/set_tags', ['cid' => $clientId, 'tag_list' => $tags]);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return true;
    }

    /**
     * 获取指定设备号的标签
     *
     * @param $clientId
     * @return bool
     * @throws ApiException
     */
    public function getTags($clientId)
    {
        $ret = $this->requestCal('GET', $this->baseUri . '/get_tags/' . $clientId);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return $ret['tags'];
    }

    /**
     * 设置黑名单设备号列表
     *
     * @param array $clientIds
     * @return bool
     * @throws ApiException
     */
    public function setBlkList(array $clientIds)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/user_blk_list', ['cid' => $clientIds]);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return true;
    }

    /**
     * 移除黑名单设备列表
     *
     * @param array $clientIds
     * @return bool
     * @throws ApiException
     */
    public function deleteBlkList(array $clientIds)
    {
        $ret = $this->requestCal('DELETE', $this->baseUri . '/user_blk_list', ['cid' => $clientIds]);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return true;
    }

    /**
     * 获取设备状态
     *
     * @param $clientId
     * @return mixed
     * @throws ApiException
     */
    public function getStatus($clientId)
    {
        $ret = $this->requestCal('GET', $this->baseUri . '/user_status/' . $clientId);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return $ret;
    }

    /**
     * 获取指定任务的推送结果
     *
     * @param $taskIds
     * @return mixed
     * @throws ApiException
     */
    public function getPushResult(array $taskIds)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/push_result', ['taskIdList' => $taskIds]);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return $ret['data'];
    }

    /**
     * 获取指定日期的用户数据
     *
     * @param string $date
     * @return mixed
     * @throws ApiException
     */
    public function getAppUser($date)
    {
        $ret = $this->requestCal('GET', $this->baseUri . '/query_app_user/' . $date);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['result']);
        }
        return $ret['data'];
    }

    /**
     * 获取指定日期的推送数据
     *
     * @param string $date
     * @return mixed
     * @throws ApiException
     */
    public function getAppPush($date)
    {
        $ret = $this->requestCal('GET', $this->baseUri . '/query_app_push/' . $date);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['result']);
        }
        return $ret['data'];
    }

    /**
     * 设置APP角标数（仅IOS）
     *
     * @param array $clientIds
     * @param int $badge 负数或者整数
     * @param array $devices
     * @return mixed
     * @throws ApiException
     */
    public function setBadge(array $clientIds, $badge, array $devices)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/set_badge', ['cid_list' => $clientIds, 'badge' => $badge, 'devicetoken_list' => $devices]);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['desc']);
        }
        return true;
    }

    /**
     * 根据任务组名获取推送结果
     * 返回结果包括百日内联网用户数（活跃用户数）、实际下发数、到达数、展示数、点击数。
     *
     * @param $groupName
     * @return mixed
     * @throws ApiException
     */
    public function getPushResultByGroupName($groupName)
    {
        $ret = $this->requestCal('GET', $this->baseUri . '/get_push_result_by_group_name/' . $groupName);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['result']);
        }
        return $ret;
    }

    /**
     * 当前时间一天内的在线数
     *
     * @return mixed
     * @throws ApiException
     */
    public function getActiveCountByToday()
    {
        $ret = $this->requestCal('GET', $this->baseUri . '/get_last_24hours_online_User_statistics');
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['result']);
        }
        return $ret['onlineStatics'];
    }

    /**
     * 通过条件获取满足条件的设备数量
     *
     * @param array $conditions
     * @return mixed
     * @throws ApiException
     */
    public function getClientCountByConditions(array $conditions)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/query_user_count', ['conditions' => $conditions]);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['result']);
        }
        return $ret['user_count'];
    }

    /**
     * 获取bi标签列表
     *
     * @return mixed
     * @throws ApiException
     */
    public function getBiTags()
    {
        $ret = $this->requestCal('GET', $this->baseUri . '/query_bi_tags');
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['result']);
        }
        return $ret['tags'];
    }

    /**
     * 获取有回执的用户列表
     *
     * @param array $params
     * @return mixed
     * @throws ApiException
     */
    public function getFeedbackUsers(array $params)
    {
        $ret = $this->requestCal('POST', $this->baseUri . '/get_feedback_users', ['data' => $params]);
        if ($ret['result'] != 'ok') {
            throw new ApiException($ret['result']);
        }
        return $ret['hasfb'];
    }
}