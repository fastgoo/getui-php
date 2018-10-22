<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/10/19
 * Time: 下午3:07
 */

namespace GeTui;

use Symfony\Component\Cache\Simple\FilesystemCache;
use GuzzleHttp\Client as HttpClient;

trait ClientManage
{

    /**
     * 请求uri
     * @var string
     */
    protected $baseUri = 'https://restapi.getui.com/v1/';

    /**
     * 授权token
     * @var string
     */
    protected $autToken;

    /**
     * 文件缓存实例
     * @var FilesystemCache
     */
    protected $cache;

    /**
     * 配置数组
     * @var array
     */
    protected $config;

    /**
     * 设置配置
     * @param array $config
     */
    public function init(array $config)
    {
        $this->config = $config;
        $this->baseUri .= $this->config['app_id'];
        $this->cache = new FilesystemCache();
    }

    /**
     * 获取鉴权的authToken
     * @return mixed
     * @throws ApiException
     */
    public function getAuthToken()
    {
        if ($this->autToken) {
            return $this->autToken;
        }
        if ($this->cache->has('auth_token.' . $this->config['app_id'])) {
            $this->autToken = $this->cache->get('auth_token.' . $this->config['app_id']);
            return $this->autToken;
        }
        $this->autToken = $this->auth();
        $this->cache->set('auth_token.' . $this->config['app_id'], $this->autToken, 3600 * 24);
        return $this->autToken;
    }

    /**
     * 个推鉴权
     * @return mixed
     * @throws ApiException
     */
    public function auth()
    {
        $data = [
            'appkey' => $this->config['app_key'],
            'timestamp' => (int)floor(microtime(true) * 1000),
        ];
        $data['sign'] = hash('sha256', "{$data['appkey']}{$data['timestamp']}{$this->config['master_secret']}");
        $ret = $this->request('POST', $this->baseUri . '/auth_sign', $data, false);
        if ($ret['result'] != 'ok') {
            throw new ApiException('鉴权失败');
        }
        return $ret['auth_token'];
    }

    /**
     * 销毁授权token，删除auth缓存
     * @return bool
     * @throws ApiException
     */
    public function authDestroy()
    {
        $ret = $this->request('POST', $this->baseUri . '/auth_close', []);
        if ($ret['result'] != 'ok') {
            return false;
        }
        $this->cache->delete('auth_token.' . $this->config['app_id']);
        $this->autToken = '';
        return true;
    }

    /**
     * 发送http请求
     * @param $method
     * @param $url
     * @param array $data
     * @param bool $is_auth
     * @return mixed
     * @throws ApiException
     */
    protected function request($method, $url, array $data = [], $is_auth = true)
    {
        $client = new HttpClient(['timeout' => 5.0]);
        $response = $client->request($method, $url, [
            'json' => $data,
            'headers' => [
                'authtoken' => $is_auth ? $this->getAuthToken() : '',
            ]
        ]);
        if ($response->getStatusCode() != 200) {
            throw new ApiException('请求个推服务器接口出现了异常，响应状态：' . $response->getStatusCode());
        }
        $ret = json_decode($response->getBody()->getContents(), true);
        if (!$ret) {
            throw new ApiException('个推响应结果异常，异常内容：' . $response->getBody()->getContents());
        }
        return $ret;
    }
}