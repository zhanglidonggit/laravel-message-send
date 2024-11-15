<?php

namespace MessageNotification\Gateways;

use MessageNotification\Interface\MessageInterface;

class DingdingGateway extends Gateway
{
    public function __construct($corpid, $corpsecret, $agentid, $token_url, $send_url, $other_params = [])
    {
        parent::__construct($corpid, $corpsecret, $agentid, $token_url, $send_url, $other_params);
        $this->key = 'dingding_access_token_'.$corpid;
    }

    public function send(MessageInterface $message)
    {
        try {
            $headers = [
                'Content-Type' => 'application/json',
                'x-acs-dingtalk-access-token' => $this->getToken(),
            ];
            $this->setHeader($headers);

            //添加机器人code
            if (! $message->hasAttribute('robotCode')) {
                $message->setAttrbute('robotCode', $this->corpid);
            }

            //兼容钉钉通知 双层json参数要求
            if ($message->hasAttribute('msgParam') && is_array($message->getAttrbute('msgParam'))) {
                $message->setAttrbute('msgParam', json_encode($message->getAttrbute('msgParam')));
            }

            return $this->sendRequestToClient($message);
        } catch (\Exception $ex) {
            return [
                'code' => $ex->getCode(),
                'message' => $ex->getMessage() ?? '消息发送失败',
            ];
        }
    }

    public function assertSuccessfully(?array $response, string $check_code = 'errcode', string $msg_code = 'errmsg')
    {
        if (! isset($response[$check_code])) {
            if (! isset($response['processQueryKey']) || isset($response['processQueryKey']) && ! $response['processQueryKey']) {
                throw new \Exception($data[$msg_code] ?? '消息发送失败', $data[$msg_code] ?? 1);
            }
        } else {
            if ((int) $response[$check_code] != 0) {
                throw new \Exception($data[$msg_code] ?? '请求失败', $data[$msg_code] ?? 1);
            }
        }

        return [
            'code' => 0,
            'message' => 'success',
            'response_data' => $response,
        ];
    }

    public function refresh()
    {
        $query = [
            'appKey' => $this->corpid,
            'appSecret' => $this->corpsecret,
        ];
        $data = $this->postJson($this->token_url, $query, ['Content-Type' => 'application/json;charset=UTF-8']);

        if ($data['accessToken'] ?? '') {
            $this->cache->set($this->key, $data['accessToken'], $data['expireIn']);

            return $data['accessToken'];
        }

        throw new \Exception('获取Token失败'.json_encode($data));
    }
}
