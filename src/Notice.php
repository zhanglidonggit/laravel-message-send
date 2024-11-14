<?php

namespace MessageNotification;

use MessageNotification\Gateways\Gateway;

class Notice
{
    public array $config;
    public Gateway $gateway;
    public function __construct(?array $config = null)
    {
        if (! $config) {
            $config = config('notice');
        }
        $channel = $config['default'] ?? '';
        $config = $config['channels'][$channel];

        if (! $channel || ! $config) {
            throw new \Exception('初始化失败');
        }
        $channelGatewayName = '';
        $channelGatewayName = __NAMESPACE__."\\Gateways\\{$channel}Gateway";

        $this->gateway = new $channelGatewayName($config['corpid'], $config['corpsecret'], $config['agentid'], $config['token_url'], $config['send_url']);

        if (! $this->gateway instanceof $channelGatewayName) {
            throw new \Exception('初始化失败');
        }
    }

    // public function checkPackageIsRegistered()
    // {
    //     $registeredPackages = config('app.providers');
    //     $current_class_name = get_called_class().'ServiceProvider::class';

    //     if (in_array($current_class_name, $registeredPackages)) {
    //         return true;
    //     }

    //     return false;
    // }

    public function send(array|string $message)
    {
        return $this->gateway->send(new Message($message));
    }
}
