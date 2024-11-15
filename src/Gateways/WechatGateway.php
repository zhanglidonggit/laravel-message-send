<?php

namespace MessageNotification\Gateways;

use MessageNotification\Interface\MessageInterface;

class WechatGateway extends Gateway
{
    public function __construct($corpid, $corpsecret, $agentid, $token_url, $send_url, $other_params = [])
    {
        parent::__construct($corpid, $corpsecret, $agentid, $token_url, $send_url, $other_params);
        $this->key = 'wechat_access_token_'.$corpid;
    }

    public function getSendUrl()
    {
        return $this->send_url.'?access_token='.$this->getToken();
    }

    public function send(MessageInterface $message)
    {
        try {
            $this->setHeader([
                'Content-Type' => 'application/json',
            ]);
            
            if(! $message->hasAttribute('agentid') || ($message->hasAttribute('agentid') && !$message->getAttrbute('agentid'))){
                $message->setAttrbute('agentid', $this->agentid);
            }

            return $this->sendRequestToClient($message);
        } catch (\Exception $ex) {
            return [
                'code' => $ex->getCode(),
                'message' => $ex->getMessage() ?? '消息发送失败',
            ];
        }
    }

    public function refresh()
    {
        $query = [
            'corpid' => $this->corpid,
            'corpsecret' => $this->corpsecret,
        ];
        $data = $this->get($this->token_url, $query, []);

        if ($data['access_token'] ?? '') {
            $this->cache->set($this->key, $data['access_token'], $data['expires_in']);

            return $data['access_token'];
        }

        throw new \Exception('获取Token失败'.json_encode($data));
    }
}
