<?php

namespace MessageNotification\Gateways;

use MessageNotification\Interface\AccessTokenInterface;
use MessageNotification\Interface\GatewayInterface;
use MessageNotification\Interface\MessageInterface;
use MessageNotification\Trait\HttpRequestTrait;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

abstract class Gateway implements AccessTokenInterface, GatewayInterface
{
    use HttpRequestTrait;
    protected string $corpid;
    protected string $corpsecret;
    protected int $agentid;
    protected string $key;
    protected string $cacheNameSpace = 'notice_message_namespace';
    protected ?CacheInterface $cache = null;
    protected int $cacheLifetime = 1500;
    protected string $token_url;
    protected string $send_url;
    protected array $headers = [];
    protected mixed $robotCode;
    public function __construct($corpid, $corpsecret, $agentid, $token_url, $send_url)
    {
        $this->corpid = $corpid;
        $this->corpsecret = $corpsecret;
        $this->agentid = $agentid;
        $this->token_url = $token_url;
        $this->send_url = $send_url;

        if (! $this->cache) { //初始化缓存存储器
            $this->cache = new Psr16Cache(new FilesystemAdapter($this->cacheNameSpace, $this->cacheLifetime));
        }
    }
    public function setHeader(?array $header = [])
    {
        if ($header) {
            $this->headers = array_merge($this->headers, $header);
        }
    }

    public function getSendUrl()
    {
        return $this->send_url;
    }

    public function getHeader()
    {
        return $this->headers;
    }
    public function getName()
    {
        return \strtolower(str_replace([__NAMESPACE__.'\\', 'Gateway'], '', \get_class($this)));
    }

    public function sendRequestToClient(MessageInterface $message)
    {
        $header = $this->getHeader();
        $request_url = $this->getSendUrl();

        try {
            $messageBody = $message->toArray();
            // dd($messageBody);
            $response = $this->postJson($request_url, $messageBody, $header);

            return $this->assertSuccessfully($response);
        } catch (\Exception $ex) {
            return [
                'code' => $ex->getCode(),
                'message' => $ex->getMessage() ?? '消息发送失败',
            ];
        }
    }

    public function assertSuccessfully(?array $response, string $check_code = 'errcode', string $msg_code = 'errmsg')
    {
        if (! isset($response[$check_code]) || isset($response[$check_code]) && (int) $response[$check_code] != 0) {
            throw new \Exception($response[$msg_code] ?? '消息发送失败', $response[$check_code] ?? 1);
        }

        return [
            'code' => 0,
            'message' => 'success',
        ];
    }

    public function getToken()
    {
        if ($this->cache->get($this->key)) {
            return $this->cache->get($this->key);
        }

        try {
            return $this->refresh();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
