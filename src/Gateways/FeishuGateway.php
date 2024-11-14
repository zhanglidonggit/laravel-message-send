<?php

namespace MessageNotification\Gateways;

use MessageNotification\Interface\MessageInterface;
use MessageNotification\Message;

class FeishuGateway extends Gateway
{
    public function __construct($corpid, $corpsecret, $agentid, $token_url, $send_url)
    {
        parent::__construct($corpid, $corpsecret, $agentid, $token_url, $send_url);
        $this->key = 'feishu_access_token_'.$corpid;
    }

    public function send(MessageInterface $message)
    {
        try {
            $headers = [
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => 'Bearer '.$this->getToken(),
            ];
            $this->setHeader($headers);

            //兼容飞书通知
            if ($message->hasAttribute('content') && Message::is_json($message->getAttrbute('content'))) {
                $message->setAttrbute('content', json_decode($message->getAttrbute('content'), true));
            }

            return $this->sendRequestToClient($message);
        } catch (\Exception $ex) {
            return [
                'code' => $ex->getCode(),
                'message' => $ex->getMessage() ?? '消息发送失败',
            ];
        }
    }

    public function assertSuccessfully(?array $response, string $check_code = 'code', string $msg_code = 'msg')
    {
        if (! isset($response[$check_code]) || isset($response[$check_code]) && (int) $response[$check_code] != 0) {
            throw new \Exception($response[$msg_code] ?? '消息发送失败', (int) $response[$check_code] ?? 1);
        }

        return [
            'code' => 0,
            'message' => 'success',
        ];
    }

    public function refresh()
    {
        $query = [
            'app_id' => $this->corpid,
            'app_secret' => $this->corpsecret,
        ];
        $data = $this->postJson($this->token_url, $query, ['Content-Type' => 'application/json; charset=utf-8']);

        if ($data['tenant_access_token'] ?? '') {
            $this->cache->set($this->key, $data['tenant_access_token'], $data['expire']);

            return $data['tenant_access_token'];
        }

        throw new \Exception('获取Token失败'.json_encode($data));
    }
}
